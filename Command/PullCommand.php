<?php
namespace Languara\SymfonyBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class PullCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $languara = \Languara\SymfonyBundle\Wrapper\LanguaraWrapper::get_instance();
        
        $this
            ->setName('languara:pull')
            ->setDescription($languara->get_message_text('notice_pull_command_info'))
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $languara = \Languara\SymfonyBundle\Wrapper\LanguaraWrapper::get_instance($this->getContainer()->get('kernel')->getRootdir());
        
        echo PHP_EOL;
        $languara->print_message('notice_starting_download', 'SUCCESS');
        echo PHP_EOL;
        
        try
        {
            $languara->download_and_process();            
        } 
        catch (\Exception $ex) 
        {
            echo PHP_EOL;
            ($languara->print_message($ex->getMessage(), 'FAILURE'));
            echo PHP_EOL;
            return;
        }
        
        echo PHP_EOL;
        $languara->print_message('success_download_successful', 'SUCCESS');
        echo PHP_EOL . PHP_EOL;
    }
}