<?php

namespace App\Filament\Resources\DomainResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DnsRecordsRelationManager extends RelationManager
{
    protected static string $relationship = 'dnsRecords';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->notRegex('/[\*]+/i')
                    ->ascii()
                    ->nullable(),
                Forms\Components\Select::make('type')
                    ->options([
                        'A'     => 'A',
                        'AAAA'  => 'AAAA',
                        'CNAME' => 'CNAME',
                        'MX'    => 'MX',
                        'SRV'   => 'SRV',
                        'TXT'   => 'TXT',
                        'NS'    => 'NS',
                    ])
                    ->required()
                    ->reactive(),
                Forms\Components\TextInput::make('data')
                    ->columnSpan(2)
                    ->required()
                    ->rule(new \App\Rules\DnsRecord),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
