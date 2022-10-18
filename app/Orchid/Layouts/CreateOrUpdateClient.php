<?php

namespace App\Orchid\Layouts;

use App\Models\Client;
use App\Models\Service;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layouts\Rows;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;

class CreateOrUpdateClient extends Rows
{
    /**
     * Used to create the title of a group of form elements.
     *
     * @var string|null
     */
    protected $title;

    /**
     * Get the fields elements to be displayed.
     *
     * @return Field[]
     */
    protected function fields(): array
    {
        $isClientExist = is_null($this->query->getContent('client')) === false;
        return [
            Input::make('client.id')->type('hidden'),
            Input::make('client.phone')
                ->required()
                ->title('Телефон')
                ->mask('(999) 999-9999')
                ->readonly($isClientExist),
            Group::make([
                Input::make('client.name')->required()->title('Имя'),
                Input::make('client.last_name')->required()->title('Фамилия'),
            ]),
            Input::make('client.email')->type('email')->title('Email'),
            DateTimer::make('client.birthday')->required()->format('Y-m-d')->title('День рождения'),
            Relation::make('client.service_id')->fromModel(Service::class, 'name')
                ->title('Тип услуги')->required(),

            Select::make('client.assessment')->required()->options([
                'Отлично' => 'Отлично',
                'Хорошо' => 'Хорошо',
                'Удовлетворительно' => 'Удовлетворительно',
                'Отвратительно' => 'Отвратительно',
            ])->help('Реакция на оказанную услугу')->empty('Не известно', 'Не известно')
        ];
    }
}
