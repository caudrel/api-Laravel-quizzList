<?php

namespace App\Http\Resources\Category;

use App\Http\Resources\Quiz\QuizResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=> $this->id,
            'title' => $this->title,
            'quizzes' => QuizResource::collection($this->whenLoaded('quizzes'))
        ];
    }
}
