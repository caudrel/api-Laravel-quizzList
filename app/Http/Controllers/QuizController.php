<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreQuizRequest;
use App\Http\Requests\UpdateQuizRequest;
use App\Http\Resources\Quiz\QuizCollection;
use App\Models\Quiz;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;


class QuizController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;

    public function index(Request $request): QuizCollection
    {
        return new QuizCollection(
            Quiz::with('category')->get()
        );
    }

    public function store(StoreQuizRequest $storeQuizRequest): Response
    {
        try {
            Quiz::create($storeQuizRequest->validated());
            return response('Le quiz a été créé', 200);
        } catch (\Exception $e) {
            Log::error('Erreur : ' . $e->getMessage(), ['exception' => $e]);
            return response("une erreur est survenue" . $e->getMessage(), 500);
        }
    }

    public function update(UpdateQuizRequest $updateQuizRequest, $id): Response
    {
        try {
            $quiz = Quiz::findOrFail($id);
            $quiz->update($updateQuizRequest->validated());
            return response("Le quiz {$quiz->name} a été mis à jour", 200);
        } catch (ModelNotFoundException $e) {
            return response("Le quiz n'existe pas", 404);
        } catch (\Exception $e) {
            Log::error('Erreur : ' . $e->getMessage(), ['exception' => $e]);
            return response("Une erreur est survenue lors de la mise à jour du quiz", 500);
        }
    }

    public function delete($id): Response
    {
        try {
            Quiz::findOrFail($id)->delete();
            return response('Le quiz a été supprimé', 200);
        } catch (ModelNotFoundException $e) {
            return response("Le quiz n'existe pas", 404);
        } catch (\Exception $e) {
            Log::error('Erreur : ' . $e->getMessage(), ['exception' => $e]);
            return response("Une erreur est survenue lors de la suppression du quiz", 500);
        }
    }

}
