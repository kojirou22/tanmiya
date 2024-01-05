<?php

namespace App\Filament\Resources;

use layout;
use Filament\Forms;
use Filament\Tables;
use App\Models\Owner;
use App\Models\Type; 
use App\Models\Project;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ProjectResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope; 
use App\Filament\Resources\ProjectResource\RelationManagers;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    protected static ?string $navigationGroup = 'Projects'; 
    
    protected static ?int $navigationSort = 0;

    protected static ?string $recordTitleAttribute = 'project_no';

    public static function getGloballySearchableAttributes(): array
    {
        return ['project_no', 'address'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Owner' => $record->owner->name 
        ];
    } 


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()
                ->schema([
                    Section::make()
                    ->schema([ 
                        TextInput::make('project_no')->required(),
                        TextInput::make('batch'),
                        Select::make('owner_id')
                        ->label('Owner')
                        ->relationship('owner', 'name')
                        // ->options(Owner::all()->pluck('name','id'))
                        ->searchable()
                        ->createOptionForm([
                            TextInput::make('name')
                                ->required(),
                            TextInput::make('designation'),
                        ])
                        ->required(),
                        TextInput::make('address'),
                        Select::make('type_id') 
                        ->label('Type')
                        ->relationship('type', 'name')  
                        ->preload()
                        ->required()
                        ->searchable(),
                        TextInput::make('cost')
                        ->prefix('₱')
                        ->numeric() 
                    ])->columns(2),
                ]),
                
                Group::make()
                ->schema([
                    Section::make()
                    ->schema([    
                        FileUpload::make('image')
                        ->directory('form-attachments')
                        ->preserveFilenames()
                        ->label('اتفاقية تنفيذ المشروع')
                        ->columnSpanFull(),
                        Select::make('status') 
                        ->options([
                            'pending' => 'Pending',
                            'ongoing' => 'On going',
                            'inspecting' => 'For Inspection',
                            'Completed' => 'Completed', 
                        ])
                        ->default('pending','Pending')
                    ]) 
                ])
                
            ]
        );
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('project_no')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('batch')
                    ->searchable()
                    ->sortable()
                    ->toggleable(), 
                TextColumn::make('owner.name')
                    ->searchable()
                    ->sortable()
                    ->toggleable(), 
                TextColumn::make('address')
                    ->searchable()
                    ->sortable()
                    ->toggleable(), 
                TextColumn::make('type.name')
                    ->searchable()
                    ->toggleable() 
                    ->sortable(), 
                IconColumn::make('status')
                ->size(IconColumn\IconColumnSize::Medium),
                // TextColumn::make('status')
                //     ->searchable()
                //     ->toggleable() 
                //     ->sortable(), 
                TextColumn::make('cost') 
                    ->toggleable() 
                    ->sortable()
                    ->toggledHiddenByDefault(true) 
            ])
            ->filters([
                SelectFilter::make('owner') 
                    ->relationship('owner', 'name') 
                    ->searchable()
                    ->preload(),
                SelectFilter::make('type')
                    ->relationship('type', 'name')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('status') 
                    ->options([
                        'pending' => 'Pending',
                        'ongoing' => 'On going',
                        'inspecting' => 'For Inspection',
                        'Completed' => 'Completed', 
                    ])
                    //->default('ongoing','On going')
            ], layout: FiltersLayout::Modal)
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProject::route('/create'),
            'edit' => Pages\EditProject::route('/{record}/edit'),
        ];
    }
}
