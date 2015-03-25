<?php
namespace Languara\SymfonyBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class TranslateCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $languara = \Languara\SymfonyBundle\Wrapper\LanguaraWrapper::get_instance();
        
        $this
            ->setName('languara:translate')
            ->setDescription($languara->get_message_text('notice_translate_command_info'))
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $languara = \Languara\SymfonyBundle\Wrapper\LanguaraWrapper::get_instance($this->getContainer()->get('kernel')->getRootdir());
        
        echo PHP_EOL;
        $languara->print_message('notice_start_translate', 'SUCCESS');
        echo PHP_EOL;
        
        try
        {
            $languara->translate();            
        } 
        catch (\Exception $ex) 
        {
            echo PHP_EOL;
            ($languara->print_message($ex->getMessage(), 'FAILURE'));
            echo PHP_EOL;
            return;
        }
        
        echo PHP_EOL;
        $languara->print_message('success_translate_completed', 'SUCCESS');
        echo PHP_EOL . PHP_EOL;
    }
}