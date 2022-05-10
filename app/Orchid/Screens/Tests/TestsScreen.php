<?php

namespace App\Orchid\Screens\Tests;

use App\Models\Test;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Orchid\Platform\Models\User;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Matrix;
use Orchid\Screen\Fields\Quill;
use Orchid\Screen\Fields\SimpleMDE;
use Orchid\Screen\Layouts\Modal;
use Orchid\Screen\Repository;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;
use Illuminate\Http\Request;

class TestsScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'tests' => Test::all(),
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Тесты';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            ModalToggle::make('Новый тест')
                ->modal('createTest')
                ->icon('brush')
                ->method('createTest')
        ];
    }

    /**
     * Сохранение теста в БД
     */
    public function createTest(Request $request): void {
        $help = $request->needHelp === '1';//пыха даёт 1, вместо true, поэтому такая вот штука теперь живёт здесь
        if ($help) {
            // Если ручная проверка, то мы не собираем правильные и неправильные ответы
            $correct[]   = [];
            $incorrect[] = [];
        } else {

            //матрица ответов
            $matrix = $request->matrix;
            //проверяем, если правильный, то в массив с правильными
            foreach ($matrix as $line) {
                if ($line['correct'] === '1') $correct[] = $line['answers']; else $incorrect[] = $line['answers'];
            }
        }

        // Отправляем тест в БД
        Test::create([
            'name'              => $request->name,
            'brefing'           => $request->brefing,
            'question'          => $request->question,
            'incorrect_answers' => json_encode($incorrect),
            'correct_answers'   => json_encode($correct),
            'points'            => $request->points,
            'needHelp'          => $help,
        ]);
        Toast::info('Добавили тест!');
    }

    /**
     * Изменение теста в БД
     */
    public function editTest(Request $request, Test $test): void {
        // Отправляем тест в БД

        if ($request->test['newAnswers']){
            $help = $request->test['needHelp'] === '1';
            if ($help) {
                // Если ручная проверка, то мы не собираем правильные и неправильные ответы
                $correct[]   = [];
                $incorrect[] = [];
            } else {
                //матрица ответов
                $matrix = $request->test['matrix'];
                //проверяем, если правильный, то в массив с правильными
                foreach ($matrix as $line) {
                    if ($line['correct'] === '1') $correct[] = $line['answers']; else $incorrect[] = $line['answers'];
                }
                $updateTest = [
                    'name'              => $request->test['name'],
                    'brefing'           => $request->test['brefing'],
                    'question'          => $request->test['question'],
                    'incorrect_answers' => json_encode($incorrect),
                    'correct_answers'   => json_encode($correct),
                    'points'            => $request->test['points'],
                    'needHelp'          => $request->test['needHelp'],
                ];
                Test::find($request->input('test.id'))->update($updateTest);
                Toast::info('Тест изменен...');
            }
        } else {
            $updateTest = [
                'name'              => $request->test['name'],
                'brefing'           => $request->test['brefing'],
                'question'          => $request->test['question'],
                'points'            => $request->test['points'],
                'needHelp'          => $request->test['needHelp'],
            ];
            Test::find($request->input('test.id'))->update($updateTest);
            Toast::info('Тест изменен...');
        }
    }


    /**
     * Удаление теста
     * @param $id
     */
    public function deleteTest($id): void
    {
        $test = Test::find($id);
        $test->delete();
    }

    public function asyncGetTest(Test $test): array
    {
        return [
            'test' => Test::find($test->id),
        ];
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            Layout::modal('createTest', Layout::rows([
                Input::make('name')->required()->title('Название'),
                SimpleMDE::make('brefing')
                    ->title('Брифинг')
                    ->required()
                    ->popover('Заполнять как текст, будет отображен в начале уровня'),
                SimpleMDE::make('question')
                    ->required()
                    ->title('Вопрос'),
                CheckBox::make('needHelp')->title('Ответ в виде файла')->sendTrueOrFalse(),
                Matrix::make('matrix')
                    ->columns([
                        'Ответ' => 'answers',
                        'Правильный' => 'correct',
                    ])
                    ->required()
                    ->fields([
                        'answers' => Input::make(),
                        'correct' => CheckBox::make()->sendTrueOrFalse(),
                    ]),
                Input::make('points')->required()->title('Количество очков за тест'),
            ]))->title('Создаем тест')
                ->applyButton('Создать')
                ->closeButton('Закрыть')
                ->size(Modal::SIZE_LG),

            Layout::modal('editTest', Layout::rows([
                Input::make('test.id')->type('hidden'),
                Input::make('test.name')->required()->title('Название'),
                SimpleMDE::make('test.brefing')
                    ->title('Брифинг')
                    ->popover('Заполнять как текст, будет отображен в начале уровня'),
                SimpleMDE::make('test.question')
                    ->title('Вопрос'),
                CheckBox::make('test.needHelp')->title('Ответ в виде файла')->sendTrueOrFalse()->disabled(),
                CheckBox::make('test.newAnswers')->title('Использовать новые варианты ответов')->sendTrueOrFalse(),
                Matrix::make('test.matrix')
                    ->columns([
                        'Ответ' => 'answers',
                        'Правильный' => 'correct',
                    ])
                    ->required()
                    ->fields([
                        'answers' => Input::make(),
                        'correct' => CheckBox::make()->sendTrueOrFalse(),
                    ]),
                Input::make('test.points')->required()->title('Количество очков за тест'),
            ]))->title('Редактируем тест')
                ->applyButton('Изменить')
                ->closeButton('Закрыть')
                ->size(Modal::SIZE_LG)
                ->async('asyncGetTest'),

            Layout::table('tests', [
                TD::make('id', 'ID'),
                TD::make('name', 'Название')
                    ->width('450'),
                TD::make('points', 'Количество очков за тест')
                    ->width('350'),
                TD::make('needHelp', 'Ручная проверка')->render(function (Test $test) {
                    if ($test->needHelp) return '✔';
                    else return '✖';
                }),

                TD::make(__('Actions'))
                    ->align(TD::ALIGN_CENTER)
                    ->width('100px')
                    ->render(function (Test $test) {
                        return DropDown::make()
                            ->icon('options-vertical')
                            ->list([
                                Button::make('Удалить')
                                    ->icon('trash')
                                    ->confirm(__('Once the account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.'))
                                    ->method('deleteTest', [
                                        'id' => $test->id,
                                    ]),
                                ModalToggle::make('Редактировать')
                                    ->modal('editTest')
                                    ->icon('wrench')
                                    ->method('editTest')
                                ->asyncParameters([
                                    'test' => $test->id,
                                ])
                            ]);
                    }),
            ]),
        ];
    }
}
