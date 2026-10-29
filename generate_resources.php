<?php

$resources = [
    'PresidencyRegister' => ['slug' => 'presidency-registers', 'label' => 'سجل رئاسة النيابة و السلطة القضائية', 'category' => 'presidency'],
    'SecretRegister' => ['slug' => 'secret-registers', 'label' => 'السجل المرسلات السرية', 'category' => 'secret'],
    'JusticeMinisterRegister' => ['slug' => 'justice-minister-registers', 'label' => 'سجل مرسلات وزير العدل و الوكيل العام للملك', 'category' => 'justice_minister'],
    'CircularsRegister' => ['slug' => 'circulars-registers', 'label' => 'سجل الدوريات و المناشير', 'category' => 'circulars'],
];

$baseDir = __DIR__ . '/app/Filament/Resources';

$abstractClass = <<<'PHP'
<?php

namespace App\Filament\Resources;

use App\Models\MinisterPublicRegister;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Components\Fieldset;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Hidden;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

abstract class BaseRegisterResource extends Resource
{
    protected static ?string $model = MinisterPublicRegister::class;
    
    public static ?string $category = null;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-book-open';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('sequential_number')
                    ->label('الرقم الترتيبي للسجل')
                    ->required()
                    ->numeric()
                    ->columnSpan(1),
                DatePicker::make('registration_date')
                    ->label('تاريخ التسجيل')
                    ->columnSpan(1),

                Hidden::make('category')->default(static::$category),

                Fieldset::make('الواردات')
                    ->relationship('incoming')
                    ->schema([
                        DatePicker::make('letter_date')
                            ->label('تاريخ الرسالة')
                            ->columnSpan(1),

                        TextInput::make('letter_number')
                            ->label('رقمها')
                            ->columnSpan(1),

                        DatePicker::make('arrival_date')
                            ->label('تاريخ الوصول')
                            ->columnSpan(1),

                        TextInput::make('sender')
                            ->label('اسم وموطن المرسل إليه')
                            ->columnSpan(1),

                        Textarea::make('subject')
                            ->label('الموضوع')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),

                Repeater::make('correspondences')
                    ->label('المراسلات (الصادرات والواردات)')
                    ->relationship('correspondences')
                    ->schema([
                        Select::make('type')
                            ->label('نوع المراسلة')
                            ->options([
                                'outgoing' => 'صادر',
                                'incoming' => 'وارد (جواب)',
                            ])
                            ->required()
                            ->default('outgoing')
                            ->live()
                            ->columnSpanFull(),

                        DatePicker::make('date')
                            ->label('التاريخ')
                            ->required()
                            ->columnSpan(1),

                        TextInput::make('correspondent')
                            ->label(fn (Get $get) => $get('type') === 'outgoing' ? 'المرسل إليه' : 'المصدر')
                            ->required()
                            ->columnSpan(1),

                        Textarea::make('subject_or_reply')
                            ->label(fn (Get $get) => $get('type') === 'outgoing' ? 'الموضوع' : 'الجواب')
                            ->required()
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->columnSpanFull()
                    ->collapsible()
                    ->defaultItems(0)
                    ->addActionLabel('إضافة مراسلة (صادر/وارد)'),

                Textarea::make('result_or_notes')
                    ->label('النتيجة أو الملاحظات')
                    ->rows(3)
                    ->nullable()
                    ->columnSpanFull(),
            ])
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sequential_number')
                    ->label('الرقم الترتيبي')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('registration_date')
                    ->label('تاريخ التسجيل')
                    ->date()
                    ->sortable(),

                TextColumn::make('incoming.subject')
                    ->label('موضوع الوارد')
                    ->searchable()
                    ->limit(30)
                    ->placeholder('—'),

                TextColumn::make('incoming.sender')
                    ->label('المرسل')
                    ->searchable()
                    ->limit(20)
                    ->placeholder('—'),

                TextColumn::make('correspondences_log')
                    ->label('المراسلات والردود')
                    ->html()
                    ->getStateUsing(function ($record) {
                        if (! $record || $record->correspondences->isEmpty()) {
                            return null;
                        }
                        return $record->correspondences->map(function ($c) {
                            $type = $c->type === 'outgoing' ? '<b style="color:#d9534f">صادر</b>' : '<b style="color:#5cb85c">وارد</b>';
                            $date = $c->date ? \Carbon\Carbon::parse($c->date)->format('d/m/Y') : '—';
                            $correspondent = $c->correspondent ? " ({$c->correspondent}) " : " ";
                            return "<div style='font-size: 0.85rem; line-height: 1.2; margin-bottom: 6px;'>{$type} [{$date}]{$correspondent}: {$c->subject_or_reply}</div>";
                        })->implode('');
                    })
                    ->placeholder('لا توجد مراسلات'),

                TextColumn::make('correspondences_count')
                    ->label('عدد المراسلات')
                    ->counts('correspondences')
                    ->badge()
                    ->color('info'),

                TextColumn::make('result_or_notes')
                    ->label('النتيجة أو الملاحظات')
                    ->limit(30)
                    ->searchable()
                    ->placeholder('—'),
            ])
            ->modifyQueryUsing(function ($query) {
                return $query->where('category', static::$category)
                             ->orderByRaw('strftime("%Y", registration_date) asc')
                             ->orderBy('sequential_number', 'asc');
            })
            ->recordActions([
                EditAction::make()->label('تعديل'),
                DeleteAction::make()->label('حذف'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->label('حذف المحدد'),
                ]),
            ]);
    }
}
PHP;

file_put_contents($baseDir . '/BaseRegisterResource.php', $abstractClass);

foreach ($resources as $name => $config) {
    $dir = $baseDir . '/' . $name . 's';
    if (!is_dir($dir)) mkdir($dir, 0777, true);
    if (!is_dir($dir . '/Pages')) mkdir($dir . '/Pages', 0777, true);

    $cat = $config['category'];
    $slug = $config['slug'];
    $label = $config['label'];

    // Write Resource Class
    $resourceContent = <<<PHP
<?php

namespace App\Filament\Resources\\{$name}s;

use App\Filament\Resources\BaseRegisterResource;
use App\Filament\Resources\\{$name}s\Pages\Create{$name};
use App\Filament\Resources\\{$name}s\Pages\Edit{$name};
use App\Filament\Resources\\{$name}s\Pages\List{$name}s;

class {$name}Resource extends BaseRegisterResource
{
    public static ?string \$category = '{$cat}';

    protected static ?string \$navigationLabel = '{$label}';

    protected static ?string \$modelLabel = 'إدخال السجل';

    protected static ?string \$pluralModelLabel = '{$label}';

    protected static ?string \$slug = '{$slug}';

    public static function getPages(): array
    {
        return [
            'index'  => List{$name}s::route('/'),
            'create' => Create{$name}::route('/create'),
            'edit'   => Edit{$name}::route('/{record}/edit'),
        ];
    }
}
PHP;
    file_put_contents($dir . '/' . $name . 'Resource.php', $resourceContent);

    // List Page
    $listContent = <<<PHP
<?php

namespace App\Filament\Resources\\{$name}s\Pages;

use App\Filament\Resources\\{$name}s\\{$name}Resource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class List{$name}s extends ListRecords
{
    protected static string \$resource = {$name}Resource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('export_register')
                ->label('استخراج السجل')
                ->icon('heroicon-o-printer')
                ->color('success')
                ->form([
                    \Filament\Forms\Components\TextInput::make('statistics_number')
                        ->label('رقم الاحصاء')
                        ->required(),
                    \Filament\Forms\Components\DatePicker::make('start_date')
                        ->label('تاريخ البداية')
                        ->required(),
                    \Filament\Forms\Components\DatePicker::make('end_date')
                        ->label('تاريخ النهاية')
                        ->required(),
                    \Filament\Forms\Components\Select::make('format')
                        ->label('صيغة الاستخراج')
                        ->options([
                            'html' => 'صفحة طباعة (Print / PDF)',
                            'excel' => 'ملف إكسيل (Excel)',
                        ])
                        ->default('html')
                        ->required(),
                ])
                ->action(fn (array \$data) => redirect()->route('print.minister_public_registers', array_merge(\$data, ['category' => '{$cat}']))),

            CreateAction::make(),
        ];
    }
}
PHP;
    file_put_contents($dir . '/Pages/List' . $name . 's.php', $listContent);

    // Create Page
    $createContent = <<<PHP
<?php

namespace App\Filament\Resources\\{$name}s\Pages;

use App\Filament\Resources\\{$name}s\\{$name}Resource;
use Filament\Resources\Pages\CreateRecord;

class Create{$name} extends CreateRecord
{
    protected static string \$resource = {$name}Resource::class;
    
    protected function mutateFormDataBeforeCreate(array \$data): array
    {
        \$data['category'] = '{$cat}';
        return \$data;
    }
}
PHP;
    file_put_contents($dir . '/Pages/Create' . $name . '.php', $createContent);

    // Edit Page
    $editContent = <<<PHP
<?php

namespace App\Filament\Resources\\{$name}s\Pages;

use App\Filament\Resources\\{$name}s\\{$name}Resource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class Edit{$name} extends EditRecord
{
    protected static string \$resource = {$name}Resource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
PHP;
    file_put_contents($dir . '/Pages/Edit' . $name . '.php', $editContent);
}

echo "Resources generated successfully.\n";

// Remove the old directory
$oldDir = $baseDir . '/MinisterPublicRegisters';
if (is_dir($oldDir)) {
    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($oldDir, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::CHILD_FIRST
    );

    foreach ($files as $fileinfo) {
        $todo = ($fileinfo->isDir() ? 'rmdir' : 'unlink');
        $todo($fileinfo->getRealPath());
    }
    rmdir($oldDir);
}

