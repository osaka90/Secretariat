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
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

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
                    ->default(function (Get $get) {
                        $date = $get('registration_date');
                        $year = $date ? \Carbon\Carbon::parse($date)->year : now()->year;
                        return MinisterPublicRegister::getNextSequentialNumber(static::$category, $year);
                    })
                    ->required()
                    ->numeric()
                    ->rules([
                        fn (Get $get, ?Model $record) => Rule::unique('minister_public_registers', 'sequential_number')
                            ->where(function ($query) use ($get) {
                                $category = static::$category;
                                $date = $get('registration_date');
                                $year = $date ? \Carbon\Carbon::parse($date)->year : now()->year;
                                return $query->where('category', $category)
                                             ->where('registration_year', $year);
                            })
                            ->ignore($record?->id),
                    ])
                    ->validationMessages([
                        'unique' => 'هذا الرقم الترتيبي مسجل مسبقاً في هذه السنة لهذا السجل.',
                    ])
                    ->columnSpan(1),

                DatePicker::make('registration_date')
                    ->label('تاريخ التسجيل')
                    ->default(now()->toDateString())
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set, $operation) {
                        if ($operation === 'create') {
                            $year = $state ? \Carbon\Carbon::parse($state)->year : now()->year;
                            $set('sequential_number', MinisterPublicRegister::getNextSequentialNumber(static::$category, $year));
                        }
                    })
                    ->columnSpan(1),

                Hidden::make('category')->default(static::$category),

                FileUpload::make('pdf_file')
                    ->label(fn () => static::$category === 'secret' ? 'ملف الإرسالية' : 'ملف الـ PDF (الدورية / المنشور)')
                    ->helperText(fn () => static::$category === 'secret' ? 'يمكن رفع ملف بصيغة PDF أو صورة (JPG, PNG)' : null)
                    ->disk('public')
                    ->directory(fn () => static::$category === 'secret' ? 'secret' : 'circulars')
                    ->acceptedFileTypes(fn () => static::$category === 'secret'
                        ? ['application/pdf', 'image/jpeg', 'image/png', 'image/webp', 'image/jpg']
                        : ['application/pdf']
                    )
                    ->openable()
                    ->downloadable()
                    ->columnSpanFull()
                    ->visible(fn () => in_array(static::$category, ['circulars', 'secret'])),

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

                        RichEditor::make('subject')
                            ->label('الموضوع')
                            ->toolbarButtons(['bold', 'italic', 'underline', 'strike', 'bulletList', 'orderedList', 'textColor'])
                            ->textColors([
                                '#000000' => 'Black',
                                '#ef4444' => 'Red',
                                '#10b981' => 'Green',
                                '#0ea5e9' => 'Sky',
                            ])
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

                        Select::make('correspondent')
                            ->label(fn (Get $get) => $get('type') === 'outgoing' ? 'المرسل إليه' : 'المصدر')
                            ->options(function () {
                                $options = \App\Models\Entity::pluck('name', 'name')->toArray();
                                $options['أخرى'] = 'أخرى (إضافة جهة جديدة)...';
                                return $options;
                            })
                            ->searchable()
                            ->required()
                            ->live()
                            ->columnSpan(1),

                        TextInput::make('custom_correspondent')
                            ->label('تفاصيل الجهة (أخرى)')
                            ->required(fn (Get $get) => $get('correspondent') === 'أخرى')
                            ->visible(fn (Get $get) => $get('correspondent') === 'أخرى')
                            ->columnSpan(1),

                        RichEditor::make('subject_or_reply')
                            ->label(fn (Get $get) => $get('type') === 'outgoing' ? 'الموضوع' : 'الجواب')
                            ->required()
                            ->toolbarButtons(['bold', 'italic', 'underline', 'strike', 'bulletList', 'orderedList', 'textColor'])
                            ->textColors([
                                '#000000' => 'Black',
                                '#ef4444' => 'Red',
                                '#10b981' => 'Green',
                                '#0ea5e9' => 'Sky',
                            ])
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->columnSpanFull()
                    ->collapsible()
                    ->defaultItems(0)
                    ->mutateRelationshipDataBeforeCreateUsing(function (array $data): array {
                        if (isset($data['correspondent']) && $data['correspondent'] === 'أخرى' && !empty($data['custom_correspondent'])) {
                            \App\Models\Entity::firstOrCreate(['name' => $data['custom_correspondent']]);
                            $data['correspondent'] = $data['custom_correspondent'];
                        }
                        unset($data['custom_correspondent']);
                        return $data;
                    })
                    ->mutateRelationshipDataBeforeSaveUsing(function (array $data): array {
                        if (isset($data['correspondent']) && $data['correspondent'] === 'أخرى' && !empty($data['custom_correspondent'])) {
                            \App\Models\Entity::firstOrCreate(['name' => $data['custom_correspondent']]);
                            $data['correspondent'] = $data['custom_correspondent'];
                        }
                        unset($data['custom_correspondent']);
                        return $data;
                    })
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
                    ->date('Y/m/d')
                    ->sortable(),

                TextColumn::make('incoming.subject')
                    ->label('موضوع الوارد')
                    ->searchable()
                    ->html()
                    ->formatStateUsing(fn ($state) => $state
                        ? preg_replace('/style="--color:\s*(#[0-9a-fA-F]{3,8})[^"]*"/', 'style="color: $1"', $state)
                        : '—')
                    ->placeholder('—'),

                TextColumn::make('incoming.sender')
                    ->label('المرسل')
                    ->searchable()
                    ->limit(20)
                    ->placeholder('—'),

                TextColumn::make('pdf_file')
                    ->label(fn () => static::$category === 'secret' ? 'الإرسالية' : 'دورية/منشور')
                    ->formatStateUsing(function ($state) {
                        if (! $state) {
                            return '—';
                        }
                        $label = static::$category === 'secret' ? 'عرض الإرسالية' : 'عرض الـ PDF';
                        return '<a href="/storage/'.$state.'" target="_blank" style="color: blue; text-decoration: underline; font-weight: bold;">'.$label.'</a>';
                    })
                    ->html()
                    ->visible(fn () => in_array(static::$category, ['circulars', 'secret'])),

                TextColumn::make('correspondences_log')
                    ->label('المراسلات والردود')
                    ->html()
                    ->getStateUsing(function ($record) {
                        if (! $record || $record->correspondences->isEmpty()) {
                            return null;
                        }
                        return $record->correspondences->map(function ($c) {
                            $type = $c->type === 'outgoing' ? '<b style="color:#d9534f">صادر</b>' : '<b style="color:#5cb85c">وارد</b>';
                            $date = $c->date ? \Carbon\Carbon::parse($c->date)->format('Y/m/d') : '—';
                            $correspondent = $c->correspondent ? " ({$c->correspondent}) " : " ";
                            $subject = preg_replace('/style="--color:\s*(#[0-9a-fA-F]{3,8})[^"]*"/', 'style="color: $1"', $c->subject_or_reply ?? '');
                            return "<div style='font-size: 0.85rem; line-height: 1.2; margin-bottom: 6px;'>{$type} [{$date}]{$correspondent}: {$subject}</div>";
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
            ->filters([
                SelectFilter::make('registration_year')
                    ->label('السنة')
                    ->options(function () {
                        $years = MinisterPublicRegister::where('category', static::$category)
                            ->whereNotNull('registration_year')
                            ->distinct()
                            ->orderBy('registration_year', 'desc')
                            ->pluck('registration_year', 'registration_year')
                            ->toArray();
                        $current = (int) now()->format('Y');
                        if (!isset($years[$current])) {
                            $years[$current] = $current;
                            krsort($years);
                        }
                        return $years;
                    }),
            ])
            ->modifyQueryUsing(function ($query) {
                return $query->where('category', static::$category)
                             ->orderByRaw('COALESCE(registration_year, cast(strftime("%Y", registration_date) as integer)) asc')
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