<?php

declare(strict_types=1);

namespace App\Orchid\Screens;

use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;

class PlatformScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'КАЗАНСКИЙ ГОСУДАРСТВЕННЫЙ ЭНЕРГЕТИЧЕСКИЙ УНИВЕРСИТЕТ';
    }

    /**
     * Display header description.
     *
     * @return string|null
     */
    public function description(): ?string
    {
        return 'Проект хакатона "EnergyHack 2022"';
    }


    public function goToProfilePage() {
        return redirect()->route('home');
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]
     */
    public function layout(): iterable
    {
        return [
            Layout::rows([

                Group::make([
                    Link::make('Таблица лидеров')
                        ->route('leaderboard')
                        ->icon('people'),

                    Link::make('Создать тест')
                        ->route('platform.tests')
                        ->icon('plus'),
                ]),

                Group::make([

                    Link::make('Веб-сайт')
                        ->route('home')
                        ->icon('home'),

                    Link::make('Проверить тест')
                        ->route('platform.answers')
                        ->icon('pencil'),
                ]),

            ])->title('Панель администратора'),
        ];
    }
}
