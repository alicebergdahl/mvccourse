<?php

namespace App\Controller;

use App\Entity\Players;
use App\Proj\GameService;
use App\Proj\ItemService;
use App\Proj\JsonDataService;
use App\Proj\AccessControlService;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\PlayersRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProjController extends AbstractController
{
    #[Route("/proj", name: "proj_home")]
    public function index(): Response
    {
        return $this->render('proj/welcome.html.twig', [
            'title' => 'Välkommen!',
        ]);
    }

    #[Route("/proj/about", name: "proj_about")]
    public function about(): Response
    {
        return $this->render('proj/about.html.twig');
    }

    #[Route("/proj/about/database", name: "proj_about_database")]
    public function aboutDatabase(): Response
    {
        return $this->render('proj/about_database.html.twig');
    }

    #[Route("/proj/docs", name: "proj_docs")]
    public function documentation(): Response
    {
        return $this->render('proj/docs.html.twig');
    }

    #[Route("/proj/game/{room}", name: "proj_game_room")]
    public function room(int $room, GameService $gameService, ItemService $itemService, PlayersRepository $playersRepository, SessionInterface $session, JsonDataService $jsonData, AccessControlService $accessControlService): Response
    {
        $currentPlayerId = $session->get('currentplayer');
        $currentRoom = $session->get('current_room');
    
        $accessInfo = $accessControlService->hasAccessToRoom($room, $currentPlayerId);
    
        if (!$accessInfo['access'] && $room !== $currentRoom) {
            $this->addFlash('proj-warning', $accessInfo['message']);
            return $this->redirectToRoute('proj_game_room', ['room' => $currentRoom]);
        }
        $gameService->setCurrentRoom($room);
        $currentRoom = $gameService->getCurrentRoom();
        $directions = $gameService->getAvailableDirections();
        $session->set('current_room', $room);
    
        $currentPlayerId = $session->get('currentplayer');

        $playerName = null;
        if ($currentPlayerId) {
            $player = $playersRepository->find($currentPlayerId);
            if ($player) {
                $playerName = $player->getPlayername();
            }
        }

        $playerItems = [];
        if ($currentPlayerId) {
            $playerItems = $itemService->getPlayerItems($currentPlayerId);
        }

        $items = $jsonData->loadItems(__DIR__ . '/../../data/items.json');
        $buttons = $jsonData->loadButtons(__DIR__ . '/../../data/buttons.json');

        return $this->render('proj/room.html.twig', [
            'currentRoom' => $currentRoom,
            'directions' => $directions,
            'playerName' => $playerName,
            'playerItems' => $playerItems,
            'items' => $items,
            'buttons' => $buttons,
            'playersRepository' => $playersRepository,
            'currentPlayerId' => $currentPlayerId,
        ]);
    }

    #[Route("/proj/move/{direction}", name: "proj_move")]
    public function move(string $direction, GameService $gameService): Response
    {
        $gameService->moveTo($direction);

        $currentRoom = $gameService->getCurrentRoom();
        return $this->redirectToRoute('proj_game_room', ['room' => $currentRoom]);
    }

    #[Route("/proj/start", name: "proj_start_game", methods: ["POST"])]
    public function startGame(Request $request, EntityManagerInterface $entityManager, SessionInterface $session): Response
    {
        $session->clear();

        $playerName = $request->request->get('playerName');

        $player = new Players();
        $player->setPlayername($playerName);

        $entityManager->persist($player);
        $entityManager->flush();

        $session->set('currentplayer', $player->getId());

        return $this->redirectToRoute('proj_game_room', ['room' => 1]);
    }

    #[Route("/proj/collect-item/{itemName}", name: "proj_collect_item")]
    public function collectItem(Request $request, string $itemName, ItemService $itemService, SessionInterface $session): Response
    {
        $currentPlayerId = $session->get('currentplayer');
    
        if ($currentPlayerId) {
            $itemService->addItemToPlayer($itemName, $currentPlayerId);
        }

        $referer = $request->headers->get('referer');
        return $this->redirect($referer);
    }

    #[Route("/proj/remove-item/{itemName}", name: "proj_remove_item")]
    public function removeItem(string $itemName, ItemService $itemService, SessionInterface $session, GameService $gameService): Response
    {
        $itemService->removeItemByName($itemName);

        $currentRoom = $session->get('current_room');

        return $this->redirectToRoute('proj_game_room', ['room' => $currentRoom]);
    }

    #[Route("/proj/open-treasure", name: "proj_open_treasure")]
    public function openTreasure(ItemService $itemService, SessionInterface $session): Response
    {
        $currentPlayerId = $session->get('currentplayer');

        $hasKey = $itemService->playerHasItem($currentPlayerId, 'key');

        if ($hasKey) {
            $this->addFlash('proj-success', 'Grattis, du vann!');
        } else {
            $this->addFlash('proj-warning', 'Du måste ha nyckeln för att öppna skatten!');
        }

        return $this->redirectToRoute('proj_game_room', ['room' => 5]);
    }
}