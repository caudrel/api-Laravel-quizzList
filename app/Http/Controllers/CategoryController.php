<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use App\Models\Quiz;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;
use PhpParser\Node\Expr\Cast\Object_;

class CategoryController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function index(): Collection
    {
        return Category::all();
        // ci-dessous fonctionne aussi pour récupérer les quizzes associées
       /* return Category::with('quiz')->get();*/
    }

    public function getQuizzes($id): Collection | Response
    {
        try {
            return Category::findOrFail($id)->quizzes;
        } catch (ModelNotFoundException $e) {
            return response("La catégory n'existe pas",404);
        } catch (\Exception) {
            return response("Une erreur est survenue lors de la récupération des données", 500);
        }
    }

    public function store(StoreCategoryRequest $storeCategoryRequest): Response
    {
        try {
            $validatedData = $storeCategoryRequest->validated();
            Category::create($validatedData);
            return response('La catégorie a été créée', 200);
        } catch (\Exception $e) {
            return response("une erreur est survenue" . $e->getMessage(), 500);
        }
    }

    public function update(UpdateCategoryRequest $updateCategoryRequest, $id): Response
    {
        Log::alert('juste pour voir si ça marche');
        try {
            $category = Category::findOrFail($id);
            $validatedData = $updateCategoryRequest->validated();
            Log::info($validatedData);
            $category->update($validatedData);
            return response("La catégorie {$category->title} a été mise à jour", 200);
        } catch (ModelNotFoundException $e) {
            return response("Le quiz n'existe pas", 404);
        } catch (\Exception $e) {
            return response("Une erreur est survenue lors de la mise à jour du quiz", 500);
        }
    }


    public function delete($id): Response
    {
        try {
            Category::findOrFail($id)->delete();

            Quiz::where('category_id', $id)
                ->update(['category_id' => null]);

            return response('La catégorie a été supprimé et les quiz qui lui étaient relié ont été dé-lié', 200);
        } catch (ModelNotFoundException $e) {
            return \response("La catégorie n'existe pas", 404);
        } catch (\Exception $e) {
            return \response("Une erreur est survenue lors de la suppression de la catégorie", 500);
        }

        /*Category::where('id', $id)->delete();
        return response('La catégorie a été supprimée', 200);*/
    }

}