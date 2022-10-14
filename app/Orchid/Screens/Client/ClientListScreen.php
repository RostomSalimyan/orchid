<?php

namespace App\Orchid\Screens\Client;

use App\Models\Client;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;

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
            'clients' => Client::paginate(10),
        ];
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): array
    {
        return [];
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): array
    {
        return [
            Layout::table('clients', [
                TD::make('phone', 'Телефон')->width('150px')->cantHide(),
                TD::make('status', 'Статус')->render(function (Client $client) {
                    return $client->status === 'interviewed' ? 'Опрошен' : 'Не опрошен';
                })->width('150px')->popover('Статус по результатам работы оператора'),
                TD::make('email', 'Email'),
                TD::make('assessment', 'Оценка')->width('200px'),
                TD::make('created_at', 'Дата создания')->defaultHidden(),
                TD::make('updated_at', 'Дата обновления')->defaultHidden(),
            ])
        ];
    }
}
