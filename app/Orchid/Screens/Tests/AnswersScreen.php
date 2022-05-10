<?php

namespace App\Orchid\Screens\Tests;

use App\Models\Pictures;
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
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;
use Illuminate\Http\Request;

class AnswersScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'answers' => Pictures::all(),
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
        ];
    }

    public function correct($test_id, $user_id, $answer_id){
        $test = Test::find($test_id);
        $user = User::find($user_id);
        $user->points = $user->points + $user->complexity * $test->points;
        $user->save();
        $pictures = Pictures::find($answer_id);
        $pictures->delete();
        Alert::message($user->name . ' получил ' . $user->complexity * $test->points . ' баллов');
    }

    public function incorrect($answer_id, $user_id){
        $pictures = Pictures::find($answer_id);
        $pictures->delete();
        $user = User::find($user_id);
        Alert::message($user->name . ' ответил неправильно');
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            Layout::table('answers', [
                TD::make('test_id', 'Название теста')->render(function (Pictures $pictures) {
                    $test = Test::find($pictures->test_id);
                    return $test->name;
                }),
                TD::make('user_id', 'Имя пользователя')
                    ->width('450')->render(function (Pictures $pictures) {
                        $user = User::find($pictures->user_id);
                        return $user->name;
                    }),
                TD::make('path_to_picture', 'Ответ')
                    ->width('350')->render(function (Pictures $pictures) {
                        return "<img src='/storage/$pictures->path_to_picture'
                              alt='sample'
                              class='mw-100 d-block img-fluid'>
                            ";
                    }),

                TD::make(__('Actions'))
                    ->align(TD::ALIGN_CENTER)
                    ->width('100px')
                    ->render(function (Pictures $pictures) {
                        return DropDown::make()
                            ->icon('options-vertical')
                            ->list([
                                Button::make(__('Правильно'))
                                    ->icon('check')
                                    ->confirm(__('Вы уверены, что хотите пометить ответ как правильный?'))
                                    ->method('correct', [
                                        'test_id'   => $pictures->test_id,
                                        'user_id'   => $pictures->user_id,
                                        'answer_id' => $pictures->id,
                                    ]),

                                Button::make('Неправильно')
                                    ->icon('close')
                                    ->confirm(__('Вы уверены, что хотите пометить ответ как неправильный?'))
                                    ->method('incorrect', [
                                        'answer_id' => $pictures->id,
                                        'user_id'   => $pictures->user_id,
                                    ]),
                            ]);
                    }),
            ]),
        ];
    }
}
