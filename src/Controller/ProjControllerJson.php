<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Items;
use App\Entity\Players;
use App\Proj\JsonDataService;
use App\Proj\ItemService;

/**
 * Controller för att hantera API-anrop i JSON-format för projektet.
 */
class ProjControllerJson extends AbstractController
{
    private JsonDataService $jsonDataService;

    /**
     * Konstruktormetod för att injicera JsonDataService.
     *
     * @param JsonDataService $jsonDataService
     */
    public function __construct(JsonDataService $jsonDataService)
    {
        $this->jsonDataService = $jsonDataService;
    }

    /**
     * Visar API-hemsidan med tillgängliga rutter.
     *
     * @return Response
     */
    #[Route("/proj/api", name: "proj_api")]
    public function apiHome(): Response
    {
        $routes = [
            'Visa alla föremål' => 'proj_api_items',
            'Visa alla portalknappar' => 'proj_api_buttons',
            'Visa alla föremål som spelare har i ryggsäcken' => 'proj_api_playeritems',
            'Visa alla spelare' => 'proj_api_players',
            'Lägg till föremål till spelares ryggsäck (money till spelare med id 1)' => 'proj_api_add_item',
            'Ta bort föremål från spelares ryggsäck (money från spelare med id 1)' => 'proj_api_remove_item',
        ];

        return $this->render('proj/api.html.twig', [
            'routes' => $routes,
        ]);
    }

    /**
     * Hämtar alla föremål från JSON-fil och returnerar som JSON-response.
     *
     * @return JsonResponse
     */
    #[Route("/proj/api/items", name: "proj_api_items")]
    public function getAllItemsFromJson(): JsonResponse
    {
        $items = $this->jsonDataService->loadItems(__DIR__ . '/../../data/items.json');
        
        $response = new JsonResponse($items);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        return $response;
    }

    /**
     * Hämtar alla portalknappar från JSON-fil och returnerar som JSON-response.
     *
     * @return JsonResponse
     */
    #[Route("/proj/api/buttons", name: "proj_api_buttons")]
    public function getAllButtonsFromJson(): JsonResponse
    {
        $buttons = $this->jsonDataService->loadButtons(__DIR__ . '/../../data/buttons.json');
        
        $response = new JsonResponse($buttons);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        return $response;
    }

    /**
     * Hämtar alla föremål från databasen och returnerar som JSON-response.
     *
     * @param EntityManagerInterface $entityManager
     * @return JsonResponse
     */
    #[Route("/proj/api/playeritems", name: "proj_api_playeritems")]
    public function getAllItemsFromDb(EntityManagerInterface $entityManager): JsonResponse
    {
        $items = $entityManager->getRepository(Items::class)->findAll();

        $itemArray = array_map(function ($item) {
            return [
                'id' => $item->getId(),
                'playername' => $item->getPlayername(),
                'itemname' => $item->getItemname(),
                'amount' => $item->getAmount(),
            ];
        }, $items);

        $response = new JsonResponse($itemArray);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        return $response;
    }

    /**
     * Hämtar alla spelare från databasen och returnerar som JSON-response.
     *
     * @param EntityManagerInterface $entityManager
     * @return JsonResponse
     */
    #[Route("/proj/api/players", name: "proj_api_players")]
    public function getAllPlayers(EntityManagerInterface $entityManager): JsonResponse
    {
        $players = $entityManager->getRepository(Players::class)->findAll();

        $playerArray = array_map(function ($player) {
            return [
                'id' => $player->getId(),
                'playername' => $player->getPlayername(),
            ];
        }, $players);

        $response = new JsonResponse($playerArray);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        return $response;
    }

    /**
     * Lägger till ett föremål till en spelares ryggsäck och returnerar meddelande i JSON-response.
     *
     * @param int $playerId
     * @param string $itemName
     * @param ItemService $itemService
     * @return JsonResponse
     */
    #[Route("/proj/api/playeritems/add/{playerId}/{itemName}", name: "proj_api_add_item", methods: ["GET", "POST"])]
    public function addItemToPlayer(int $playerId, string $itemName, ItemService $itemService): JsonResponse
    {
        $message = $itemService->addItemToPlayer($itemName, $playerId);

        return new JsonResponse(['message' => $message], JsonResponse::HTTP_OK);
    }

    /**
     * Tar bort ett föremål från en spelares ryggsäck och returnerar meddelande i JSON-response.
     *
     * @param int $playerId
     * @param string $itemName
     * @param ItemService $itemService
     * @return JsonResponse
     */
    #[Route("/proj/api/playeritems/remove/{playerId}/{itemName}", name: "proj_api_remove_item", methods: ["GET", "POST"])]
    public function removeItemFromPlayer(int $playerId, string $itemName, ItemService $itemService): JsonResponse
    {
        $itemService->removeItemByName($itemName);
    
        return new JsonResponse(['message' => 'Item removed'], JsonResponse::HTTP_OK);
    }
}