<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\Category\CategoryCollection;
use App\Http\Resources\Category\CategoryResource;
use App\Models\Category;
use App\Models\Quiz;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;

class CategoryController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function index(): CategoryCollection
    {
        return new CategoryCollection(
            Category::with('quizzes')->get()
        );
    }

    public function getQuizzes($slug): CategoryCollection|Response
    {
        try {
            return new CategoryCollection(
                Category::where('slug', $slug)->with('quizzes')->get()
            );
        } catch (ModelNotFoundException $e) {
            return response("La catégory n'existe pas", 404);
        } catch (\Exception $e) {
            Log::error('Erreur : ' . $e->getMessage(), ['exception' => $e]);
            return response("Une erreur est survenue lors de la récupération des données", 500);
        }
    }

    public function store(StoreCategoryRequest $storeCategoryRequest): Response
    {
        try {
            Category::create($storeCategoryRequest->validated());
            return response('La catégorie a été créée', 200);
        } catch (\Exception $e) {
            Log::error('Erreur : ' . $e->getMessage(), ['exception' => $e]);
            return response("une erreur est survenue" . $e->getMessage(), 500);
        }
    }

    public function update(UpdateCategoryRequest $updateCategoryRequest, $slug): Response
    {
        Log::alert('juste pour voir si ça marche');
        try {
            $category = Category::where('slug', $slug)->firstOrFail();
            $category->update($updateCategoryRequest->validated());
            return response("La catégorie {$category->title} a été mise à jour", 200);
        } catch (ModelNotFoundException $e) {
            return response("Le quiz n'existe pas", 404);
        } catch (\Exception $e) {
            Log::error('Erreur : ' . $e->getMessage(), ['exception' => $e]);
            return response("Une erreur est survenue lors de la mise à jour du quiz", 500);
        }
    }


    public function delete($slug): Response
    {
        try {
            $id = Category::where('slug', $slug)->firstOrFail()->id;
            Quiz::where('category_id', $id)
                ->update(['category_id' => null]);

            Category::where('slug', $slug)->firstOrFail()->delete();

            return response('La catégorie a été supprimée et les quizzes associés ne sont plus liés.', 200);
        } catch (ModelNotFoundException $e) {
            return response("La catégorie n'existe pas", 404);
        } catch (\Exception $e) {
            Log::error('Erreur : ' . $e->getMessage(), ['exception' => $e]);
            return response("Une erreur est survenue lors de la suppression de la catégorie", 500);
        }
    }

}
