<?php
declare(strict_types=1);

namespace App\Controller\Api\Api\Entity\PreparedNotificationData;

use App\Controller\Api\Api\AbstractManagerController;
use App\Handler\Controller\Api\Api\Entity\PreparedNotificationData\CommonController\AddHandler;
use App\Handler\Controller\Api\Api\Entity\PreparedNotificationData\CommonController\DeleteHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class CommonController extends AbstractManagerController
{
    /**
     * @Route("/api/api/entity/preparedNotificationData/delete", methods={"DELETE"})
     */
    public function delete(Request $request, DeleteHandler $deleteHandler): Response
    {
        if (!empty($content = $request->getContent(false))) {
            $deleteHandler->delete($this->prepareForIteration($content));
        }

        return new Response(null, 200, []);
    }

    /**
     * @Route("/api/api/entity/preparedNotificationData/add", methods={"POST"})
     */
    public function add(Request $request, AddHandler $addHandler): Response
    {
        if (!empty($content = $request->getContent(false))) {
            $addHandler->add($this->prepareForIteration($content));
        }

        return new Response(null, 200, []);
    }

    private function prepareForIteration(string $content): array
    {
        if ($content[0] != '[') {
            $content = sprintf('[%s]', $content);
        }

        return $this->safelyStringJsonDecode($content, true, 512, 0);
    }
}