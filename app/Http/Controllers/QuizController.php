<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreQuizRequest;
use App\Http\Requests\UpdateQuizRequest;
use App\Models\Quiz;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;

class QuizController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function index(): Collection
    {
       return Quiz::all();
    }

   public function store(StoreQuizRequest $storeQuizRequest): Response
   {
       // ne fonctionne dans postman qu'en "form-data" key/value, pas en "raw"
        // Validation des champs reçus (regarder FormValidation class dans la doc)
       try {
           $validatedData = $storeQuizRequest->validated();
           Quiz::create($validatedData);
           return response('Le quiz a été créé', 200);
       } catch (\Exception $e) {
           return response("une erreur est survenu" . $e->getMessage(), 500);
       }
    }

    public function update(UpdateQuizRequest $updateQuizRequest, $id): Response
    {
        try{
            $quiz = Quiz::findOrFail($id);
            $validatedData = $updateQuizRequest->validated();
            $quiz->update($validatedData);
            return response("Le quiz {$quiz->name} a été mis à jour", 200);
        } catch (ModelNotFoundException $e) {
        return response("Le quiz n'existe pas", 404);
        } catch (\Exception $e) {
        return response("Une erreur est survenue lors de la mise à jour du quiz", 500);
        }
    }

    public function delete($id): Response
    {
        // Suppression du model
        Quiz::where('id', $id)->delete();

        return response('Le quiz a été supprimé', 200);
    }

}
