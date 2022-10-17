<?php

namespace App\Orchid\Screens\Client;

use App\Http\Requests\ClientRequest;
use App\Models\Client;
use App\Models\Service;
use App\Orchid\Layouts\Client\ClientListTable;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class ClientListScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Клиенты';

    /**
     * Display header description.
     *
     * @var string|null
     */
    public $description = 'Список клиентов';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [
            'clients' => Client::filters()->defaultSort('status', 'desc')->paginate(10),
        ];
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): array
    {
        return [
            ModalToggle::make('Создать клиента')->modal('createClient')->method('createOrUpdateClient'),
        ];
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): array
    {
        return [
            ClientListTable::class,
            Layout::modal('createClient', Layout::rows([
                Input::make('client.phone')->required()->title('Телефон')->mask('(999) 999-9999'),
                Group::make([
                    Input::make('client.name')->required()->title('Имя'),
                    Input::make('client.last_name')->required()->title('Фамилия'),
                ]),
                Input::make('client.email')->type('email')->title('Email'),
                DateTimer::make('client.birthday')->required()->format('Y-m-d')->title('День рождения'),
                Relation::make('client.service_id')->fromModel(Service::class, 'name')
                    ->title('Тип услуги')->required()
            ]))->title('Создание клиента')->applyButton('Создать'),

            Layout::modal('editClient', Layout::rows([
                Input::make('client.id')->type('hidden'),
                Input::make('client.phone')->disabled()->required()->title('Телефон'),
                Group::make([
                    Input::make('client.name')->required()->placeholder('Имя клиента')->title('Имя'),
                    Input::make('client.last_name')->required()->placeholder('Фамилия клиента')
                        ->title('Фамилия')
                ]),
                Input::make('client.email')->type('email')->required()->title('Email'),
                DateTimer::make('client.birthday')->format('Y-m-d')->title('День рождения')->required(),
                Relation::make('client.service_id')->fromModel(Service::class, 'name')
                    ->title('Тип услуги')
                    ->required()
                    ->help('Один из видов оказанных услуг'),
                Select::make('client.assessment')->required()->options([
                    'Отлично' => 'Отлично',
                    'Хорошо' => 'Хорошо',
                    'Удовлетворительно' => 'Удовлетворительно',
                    'Отвратительно' => 'Отвратительно',
                ])->help('Реакция на оказанную услугу')->empty('Не известно', 'Не известно')
            ]))->async('asyncGetClient')
        ];
    }

    public function asyncGetClient(Client $client): array
    {
        return [
            'client' => $client
        ];
    }

    public function createOrUpdateClient(ClientRequest $request): void
    {
        $clientId = $request->input('client_id');

        Client::updateOrCreate([
            'id' => $clientId
        ], array_merge($request->validated()['client'], [
            'status' => 'interviewed'
        ]));

        is_null($clientId) ? Toast::info('Клиент') : Toast::info('Клиент обновлен');
    }
}






















