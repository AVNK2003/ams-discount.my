<?php

namespace App\Http\Livewire;

use App\Models\Comment;
use Livewire\Component;

class Comments extends Component
{
    public $name, $rating, $review;
    public $comments;
    public $company_id;

    protected $rules = [
        'name' => 'required|alpha_dash|min:3|max:32',
        'rating' => 'required|digits_between:1,5',
        'review' => 'max:2000',
    ];

    protected $messages = [
        'name.required' => 'Имя обязательно для заполнения',
        'name.alpha_dash' => 'В имени недопустимые символы',
        'name.min' => 'Минимальное количество символов в имени :min',
        'rating.required' => 'Необходимо поставить оценку данной организации',
        'rating.digits_between' => 'Оценка должна быть от 1 до 5 звезд',
        'review.max' => 'Максимальное количество символов для отзыва 2000',
    ];


    public function render()
    {
//        $this->comments = Comment::where('company_id', $this->company_id)->where('published', true)->get();
        return view('livewire.comments');
    }

    public function setLike($id)
    {
        $comment = $this->comments->find($id);
        $collect = session('likes', collect([]));

        if ($collect->has($id)) {
            if ($collect->get($id) == 'like') {
                $comment->likes--;
                $collect->forget($id);
            }
            else {
                $comment->dislikes--;
                $comment->likes++;
                $collect->put($id, 'like');
            }
        }

        else {
            $comment->likes++;
            $collect->put($id, 'like');
        }
        $comment->save();
        session()->put('likes', $collect);
    }

    public function setDislike($id)
    {
        $comment = $this->comments->find($id);
        $collect = session('likes', collect([]));

        if ($collect->has($id)) {
            if ($collect->get($id) == 'dislike') {
                $comment->dislikes--;
                $collect->forget($id);
            }
            else {
                $comment->likes--;
                $comment->dislikes++;
                $collect->put($id, 'dislike');
            }
        }

        else {
            $comment->dislikes++;
            $collect->put($id, 'dislike');
        }
        $comment->save();
        session()->put('likes', $collect);
    }


    public function submit()
    {
        $this->validate();

        Comment::create([
            'company_id' => $this->company_id,
            'name' => $this->name,
            'rating' => $this->rating,
            'review' => $this->review,
        ]);

        $this->resetInput();
        session()->flash('success', 'Спасибо за Ваше мнение! Отзыв отправлен на модерацию');
    }

    public function done()
    {
        session()->forget('success');
    }

    private function resetInput()
    {
        $this->name = null;
        $this->rating = null;
        $this->review = null;
    }
}
