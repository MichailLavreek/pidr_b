<?php

namespace App\SiteBundle\Controller;

use App\Entity\Content;
use App\Entity\Feedback;
use App\Entity\Language;
use App\Entity\Order;
use App\Entity\Product;
use App\Entity\Structure;
use App\SiteBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class OrderController extends BaseController
{
    /**
     * @Route("/{_locale}/order", name="order", requirements={"_locale"="ua|en|ru"}, methods={"get"})
     */
    public function indexAction(Request $request)
    {
        $this->setup($request);

        $this->responseData['structure'] = $this
            ->getDoctrine()
            ->getRepository(Structure::class)
            ->findOneBy(['alias' => 'order']);

        $this->responseData['content'] = $this
            ->getDoctrine()
            ->getRepository(Content::class)
            ->findOneBy(['structure' => $this->responseData['structure']->getId()]);

        $this->setupMeta($this->responseData['structure']);
        return $this->render('page/order.html.twig', $this->responseData);
    }

    /**
     * @Route("/{_locale}/order", name="order-montage-post", requirements={"_locale"="ua|en|ru"}, methods={"post"})
     */
    public function postAction(Request $request, $_locale)
    {
        $this->setup($request);

        $data = [];
        $data['name'] = $request->request->get('name');
        $data['phone'] = $request->request->get('phone');
        $data['address'] = $request->request->get('address');
        $data['quadrature'] = $request->request->get('quadrature');
        $data['productText'] = $request->request->get('productText');
        $data['productId'] = $request->request->get('productId');
        $data['orderDate'] = $request->request->get('orderDate');
        $data['orderQueue'] = $request->request->get('orderQueue');

        $data = $this->validate($data);

        if (!empty($data['errors'])) {
            return new JsonResponse(['errors' => $data['errors']]);
        }

        $language = $this->em->getRepository(Language::class)->find($_locale);

        $order = new Order();
        $order->setClientName($data['name']);
        $order->setClientPhone($data['phone']);
        $order->setClientAddress($data['address']);
        $order->setOrderDate($data['orderDate']);
        $order->setOrderQueue($data['orderQueue']);
        $order->setLanguage($language);
        $order->setIp($request->getClientIp());

        if (!empty($data['quadrature'])) {
            $order->setQuadrature($data['quadrature']);
        }

        if (!empty($data['productText'])) {
            $order->setProductText($data['productText']);
        }

        if (!empty($data['productId'])) {
            $product = $this->em->getRepository(Product::class)->find($data['productId']);

            if (!empty($product)) {
                $order->setProduct($product);
                $order->setProductPrice($product->getPrice());
            }
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($order);
        $em->flush($order);

        return new JsonResponse(['success' => true]);
    }

    private function validate($data)
    {
        $data['errors'] = [];

        if (empty($data['name'])) $data['errors']['name'] = 'name.empty';
        if (empty($data['phone'])) $data['errors']['phone'] = 'phone.empty';
        if (empty($data['address'])) $data['errors']['address'] = 'address.empty';
        if (empty($data['orderDate'])) $data['errors']['orderDate'] = 'orderDate.empty';
        if (empty($data['orderQueue'])) $data['errors']['orderQueue'] = 'orderQueue.empty';

        return $data;
    }

    /**
     * @Route("/order/getDatesForCalendar", name="order-get-dates", methods={"get"})
     */
    public function getDatesForCalendarAction()
    {
        $doctrine = $this->getDoctrine();
        $orderRepository = $doctrine->getRepository(Order::class);

        $orders = $orderRepository->findActiveOrders();

        $json = [];

        /** @var Order $order */
        foreach ($orders as $order) {
            $year = $order->getOrderDate()->format('Y');
            $month = $order->getOrderDate()->format('n');
            $day = $order->getOrderDate()->format('j');
            $status = $order->getStatus();

            if ($status === 'rejected') continue;

            $returnStatus = $status === 'accepted' ? 'hold' : 'half-hold';

            if (empty($json[$year])) {
                $json[$year] = [];
            }

            if (empty($json[$year][$month])) {
                $json[$year][$month] = [];
            }

            if (empty($json[$year][$month][$day])) {
                $json[$year][$month][$day] = [
                    '1' => 'free',
                    '2' => 'free',
                    '3' => 'free',
                ];
            }

            $json[$year][$month][$day][$order->getOrderQueue()] = $returnStatus;
        }

        return $this->json($json);
    }
}
