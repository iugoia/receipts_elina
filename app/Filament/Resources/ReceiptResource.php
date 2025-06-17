<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReceiptResource\Pages;
use App\Models\Receipt;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class ReceiptResource extends Resource
{
    protected static ?string $model = Receipt::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Название')
                    ->required(),
                Forms\Components\Textarea::make('description')
                    ->label('Описание')
                    ->required(),
                Forms\Components\Repeater::make('ingredients')
                    ->label('Ингредиенты')
                    ->schema([
                        TextInput::make('name')
                            ->label('Название ингредиента')
                            ->required(),
                        TextInput::make('quantity')
                            ->label('Количество')
                            ->required(),
                    ])
                    ->createItemButtonLabel('Добавить ингредиент')
                    ->minItems(1)
                    ->maxItems(20),
                Forms\Components\FileUpload::make('images')
                    ->label('Изображения')
                    ->multiple()
                    ->image()
                    ->disk('public')
                    ->directory('recipes')
                    ->required(),
                Forms\Components\RichEditor::make('instructions')
                    ->label('Инструкции')
                    ->required(),
            ]);
    }

    public static function saved(Receipt $record, array $data): void
    {
        if (isset($data['images'])) {
            foreach ($data['images'] as $image) {
                $record->addMedia($image)->toMediaCollection('images');
            }
        }

        if (isset($data['ingredients'])) {
            $ingredients = $data['ingredients']; // Если это массив, вы можете пройти по нему и сохранить в отдельную таблицу или обновить поле
            foreach ($ingredients as $ingredient) {
                // Пример сохранения ингредиентов, если они хранятся как отдельные сущности
                // Здесь будет ваша логика для сохранения ингредиентов
                $record->ingredients()->create([
                    'name' => $ingredient['name'],
                    'quantity' => $ingredient['quantity'],
                ]);
            }
        }

        // Обработка инструкций
        if (isset($data['instructions'])) {
            // Логика для обработки инструкций
            // Например, вы можете просто сохранить их в поле модели
            $record->instructions = $data['instructions'];
            $record->save();
        }
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->label('Название')->searchable(),
                Tables\Columns\TextColumn::make('description')->label('Описание')->limit(50),
                Tables\Columns\TextColumn::make('created_at')->label('Дата создания')->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListReceipts::route('/'),
            'create' => Pages\CreateReceipt::route('/create'),
            'edit'   => Pages\EditReceipt::route('/{record}/edit'),
        ];
    }
}
