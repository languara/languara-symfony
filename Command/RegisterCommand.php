<?php
namespace Languara\SymfonyBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class RegisterCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $languara = \Languara\SymfonyBundle\Wrapper\LanguaraWrapper::get_instance();
        
        $this
            ->setName('languara:register')
            ->setDescription($languara->get_message_text('notice_register_command_info'))
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $languara = \Languara\SymfonyBundle\Wrapper\LanguaraWrapper::get_instance($this->getContainer()->get('kernel')->getRootdir());
        
        echo PHP_EOL;
        $languara->print_message('notice_register_command', 'SUCCESS');
        echo PHP_EOL;
        
        try
        {
            $languara->register($languara->platform);      
        } 
        catch (\Exception $ex) 
        {
            echo PHP_EOL;
            ($languara->print_message($ex->getMessage(), 'FAILURE'));
            echo PHP_EOL;
            return;
        }
        
        echo PHP_EOL;
        $languara->print_message('success_registration_completed', 'SUCCESS');
        echo PHP_EOL . PHP_EOL;
    }
}