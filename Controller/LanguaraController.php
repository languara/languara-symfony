<?php

namespace Languara\SymfonyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Languara\SymfonyBundle\Wrapper\LanguaraWrapper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class LanguaraController extends Controller
{
    public function pullAction(Request $request)
    {
        $external_request_id = $request->request->get('external_request_id');
        $client_signature = $request->request->get('signature');
              
		$languara = LanguaraWrapper::get_instance($this->get('kernel')->getRootdir());  
        try
        {
            $languara->check_auth($external_request_id, $client_signature);
            $languara->download_and_process();
        }
        catch (\Exception $e)
        {
            $obj_response = new Response($e->getMessage());
            $obj_response->setStatusCode(Response::HTTP_BAD_REQUEST);
            return $obj_response;
        }
        
        return new Response(1);
    }
    public function pushAction(Request $request)
    {
        $external_request_id = $request->request->get('external_request_id');
        $client_signature = $request->request->get('signature');
        
		$languara = LanguaraWrapper::get_instance($this->get('kernel')->getRootdir());  
        
        try
        {
            $languara->check_auth($external_request_id, $client_signature);
            $languara->upload_local_translations();
        }
        catch (\Exception $e)
        {
            $obj_response = new Response($e->getMessage());
            $obj_response->setStatusCode(Response::HTTP_BAD_REQUEST);
            return $obj_response;
        }
        
        return new Response(1);
    }
}
