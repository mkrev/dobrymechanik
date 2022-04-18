<?php

namespace App\Controller;

use App\Action\GetAutomobileArticles;
use App\Action\GetAutomobileArticlesExtend;
use App\DTO\QueryArticle;
use App\Exception\ApiException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[Route('/nytimes', name: 'nytimes')]
class ArticleController extends AbstractController
{
    #[Route('/', name: 'list')]
    public function list(HttpClientInterface $client, ValidatorInterface $validator): Response
    {
        try {
            $queryArticle = new QueryArticle([
                'sort' => QueryArticle::NEWEST,
                'newsDesk' => 'Automobiles',
                'fieldList' => 'headline,pub_date,lead_paragraph,multimedia,web_url',
                'apiKey' => $this->getParameter('app.nytimes_api_key')
            ]);

            $errors = $validator->validate($queryArticle);

            if (count($errors) > 0) {
                return new JsonResponse(['status' => false, 'message' => $errors->get(0)->getMessage()], Response::HTTP_BAD_REQUEST);
            }

            $response = (new GetAutomobileArticles($client, $queryArticle))->handle();

            return $this->json($response);
        } catch (ApiException $e) {
            return new JsonResponse(['status' => false, 'message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        } catch (\Throwable $e) {
            return new JsonResponse(['status' => false, 'message' => 'Niespodziewany bład systemu'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }

    #[Route('/{filter}', name: 'filteredList')]
    public function filteredList(Request $request, HttpClientInterface $client, ValidatorInterface $validator, string $filter): Response
    {
        try {
            if (!$this->validApiKey($request)) {
                return new JsonResponse(['status' => false, 'message' => 'Niepoprawny API Key'], Response::HTTP_UNAUTHORIZED);
            }

            $queryArticle = new QueryArticle([
                'sort' => QueryArticle::NEWEST,
                'newsDesk' => 'Automobiles',
                'fieldList' => 'headline,pub_date,lead_paragraph,multimedia,web_url,section_name,subsection_name',
                'query' => $filter,
                'apiKey' => $this->getParameter('app.nytimes_api_key')
            ]);

            $errors = $validator->validate($queryArticle);

            if (count($errors) > 0) {
                return new JsonResponse(['status' => false, 'message' => $errors->get(0)->getMessage()], Response::HTTP_BAD_REQUEST);
            }

            $response = (new GetAutomobileArticlesExtend($client, $queryArticle))->handle();

            return $this->json($response);
        } catch (ApiException $e) {
            return new JsonResponse(['status' => false, 'message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        } catch (\Throwable $e) {
            return new JsonResponse(['status' => false, 'message' => 'Niespodziewany bład systemu'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    private function validApiKey(Request $request): bool
    {
        return $request->headers->get('authorization') === $this->getParameter('app.api_key');
    }
}
