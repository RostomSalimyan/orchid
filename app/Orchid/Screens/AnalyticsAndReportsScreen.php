<?php

namespace App\Orchid\Screens;

use App\Models\Client;
use App\Orchid\Layouts\Charts\DynamicsInterviewedClients;
use App\Orchid\Layouts\Charts\PercentageFeedbackClients;
use Orchid\Screen\Screen;

class AnalyticsAndReportsScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Аналитика и Отчёты';

    /**
     * Display header description.
     *
     * @var string|null
     */
    public $description = '';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [
            'percentageFeedback' => Client::whereNotNull('assessment')->countForGroup('assessment')->toChart(),
            'interviewedClients' => [
                Client::whereNotNull('assessment')
                    ->countByDays(null, null, 'updated_at')
                    ->toChart('Опрошенные клиенты'),
                Client::countByDays()
                    ->toChart('Новые клиенты')
            ]
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
            PercentageFeedbackClients::class,
            DynamicsInterviewedClients::class
        ];
    }
}
