<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Forms;

class Test extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.test';

    public array $data;

    protected function actions()
    {
        return [
            Action::make('Save')
                ->icon('heroicon-o-check')
                ->color('success')
                ->submit('form'),
        ];
    }

    protected function getFormStatePath(): ?string
    {
        return 'data';
    }

    protected function getFormSchema(): array
    {
        return [
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
        ];
    }

    public function save()
    {
        $data = $this->form->getState();

        //

        $this->form->reset();

        return redirect()->back();
    }
}
