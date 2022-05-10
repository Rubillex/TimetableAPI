<?php

namespace App\Orchid\Screens\Tests;

use App\Models\Pictures;
use App\Models\PicturesToTests;
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
use Orchid\Screen\Fields\Picture;
use Orchid\Screen\Fields\Quill;
use Orchid\Screen\Fields\SimpleMDE;
use Orchid\Screen\Fields\Upload;
use Orchid\Screen\Layouts\Modal;
use Orchid\Screen\Repository;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;
use Illuminate\Http\Request;

class PicturesUploadScreen extends Screen
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
        return 'Изображения';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            ModalToggle::make('Новое изображение')
                ->modal('uploadPicture')
                ->icon('arrow-up-circle')
                ->method('uploadPicture')
        ];
    }

    /**
     * Сохранение теста в БД
     */
    public function uploadPicture(Request $request, PicturesToTests $picturesToTests): void {

        $picturesToTests->fill($request->get('image'))->save();

        $picturesToTests->attachment()->syncWithoutDetaching(
            $request->input('image', [])
        );
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     * @throws \Throwable
     */
    public function layout(): iterable
    {
        return [
            Layout::modal('uploadPicture', Layout::rows([
                Upload::make('image')
                    ->maxFiles(1)
                    ->storage('public')
                    ->targetRelativeUrl(),
            ]))->title('Добавляем новое изображение')
                ->applyButton('Готово')
                ->closeButton('Закрыть')
                ->size(Modal::SIZE_LG),

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
            ]),
        ];
    }
}
