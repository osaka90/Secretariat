<?php

namespace Tests\Feature;

use App\Models\MinisterPublicRegister;
use App\Models\User;
use App\Filament\Resources\BaseRegisterResource;
use App\Filament\Resources\SecretRegisters\SecretRegisterResource;
use App\Filament\Resources\PresidencyRegisters\PresidencyRegisterResource;
use App\Filament\Resources\CircularsRegisters\CircularsRegisterResource;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class RegisterSequentialNumberAndUploadTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_next_sequential_number_increments_per_year_and_reboots_on_new_year()
    {
        // For a new year (e.g. 2030) with no records in secret register, next number should reboot to 1
        $next2030 = MinisterPublicRegister::getNextSequentialNumber('secret', 2030);
        $this->assertEquals(1, $next2030);

        // Create record 1 in 2030
        $record1 = MinisterPublicRegister::create([
            'category' => 'secret',
            'registration_date' => '2030-01-15',
        ]);
        $this->assertEquals(1, $record1->sequential_number);
        $this->assertEquals(2030, $record1->registration_year);

        // Next number in 2030 should be 2
        $this->assertEquals(2, MinisterPublicRegister::getNextSequentialNumber('secret', 2030));

        // Create record 2 in 2030
        $record2 = MinisterPublicRegister::create([
            'category' => 'secret',
            'registration_date' => '2030-02-20',
        ]);
        $this->assertEquals(2, $record2->sequential_number);
        $this->assertEquals(2030, $record2->registration_year);

        // Next number in 2030 should be 3
        $this->assertEquals(3, MinisterPublicRegister::getNextSequentialNumber('secret', 2030));

        // A new year (e.g. 2031) should reboot to 1
        $this->assertEquals(1, MinisterPublicRegister::getNextSequentialNumber('secret', 2031));

        // Create record 1 in 2031 (same sequential number 1 as 2030, but different year)
        $record2031 = MinisterPublicRegister::create([
            'category' => 'secret',
            'registration_date' => '2031-01-05',
        ]);
        $this->assertEquals(1, $record2031->sequential_number);
        $this->assertEquals(2031, $record2031->registration_year);

        // Clean up test records
        $record1->delete();
        $record2->delete();
        $record2031->delete();
    }

    public function test_secret_register_file_upload_attribute()
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->create('irsaliya.pdf', 100, 'application/pdf');
        $path = $file->store('secret', 'public');

        $record = MinisterPublicRegister::create([
            'category' => 'secret',
            'registration_date' => '2026-05-10',
            'pdf_file' => $path,
        ]);

        $this->assertEquals($path, $record->pdf_file);
        $this->assertStringStartsWith('secret/', $record->pdf_file);
        Storage::disk('public')->assertExists($path);

        $record->delete();
    }

    public function test_unique_constraint_enforced_within_same_year_and_category()
    {
        $record1 = MinisterPublicRegister::create([
            'category' => 'secret',
            'registration_date' => '2035-01-01',
            'sequential_number' => 10,
        ]);

        $this->expectException(\Illuminate\Database\QueryException::class);

        // Attempting to create duplicate sequential_number 10 in same year 2035 and category 'secret'
        MinisterPublicRegister::create([
            'category' => 'secret',
            'registration_date' => '2035-06-01',
            'sequential_number' => 10,
        ]);
    }

    public function test_resource_form_components_visibility_and_labels()
    {
        $secretForm = SecretRegisterResource::form(new \Filament\Schemas\Schema());
        $components = $secretForm->getComponents();

        // Find pdf_file component
        $pdfComponents = array_filter($components, fn ($c) => method_exists($c, 'getName') && $c->getName() === 'pdf_file');
        $this->assertNotEmpty($pdfComponents);
        $pdfComponent = reset($pdfComponents);

        $this->assertTrue($pdfComponent->isVisible());
        $this->assertEquals('ملف الإرسالية', $pdfComponent->getLabel());
        $this->assertEquals('secret', $pdfComponent->getDirectory());

        // Now check PresidencyRegisterResource form
        $presidencyForm = PresidencyRegisterResource::form(new \Filament\Schemas\Schema());
        $presComponents = $presidencyForm->getComponents();
        $presPdfComponents = array_filter($presComponents, fn ($c) => method_exists($c, 'getName') && $c->getName() === 'pdf_file');
        // Since Presidency category is neither circulars nor secret, pdf_file is not in visible components or is not visible
        $presPdf = reset($presPdfComponents);
        if ($presPdf) {
            $this->assertFalse($presPdf->isVisible());
        } else {
            $this->assertEmpty($presPdfComponents);
        }

        // Check CircularsRegisterResource form
        $circularsForm = CircularsRegisterResource::form(new \Filament\Schemas\Schema());
        $circComponents = $circularsForm->getComponents();
        $circPdfComponents = array_filter($circComponents, fn ($c) => method_exists($c, 'getName') && $c->getName() === 'pdf_file');
        $circPdf = reset($circPdfComponents);
        $this->assertTrue($circPdf->isVisible());
        $this->assertEquals('ملف الـ PDF (الدورية / المنشور)', $circPdf->getLabel());
        $this->assertEquals('circulars', $circPdf->getDirectory());
    }
}
