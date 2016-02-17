<?php
namespace Languara\SymfonyBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ConnectCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $languara = \Languara\SymfonyBundle\Wrapper\LanguaraWrapper::get_instance();
        
        $this
            ->setName('languara:connect')
            ->setDescription($languara->get_message_text('notice_translate_command_info'))
            ->addArgument(
                'private_key',
                InputArgument::OPTIONAL,
                'Project private key'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $languara = \Languara\SymfonyBundle\Wrapper\LanguaraWrapper::get_instance($this->getContainer()->get('kernel')->getRootdir());

        $private_key = $input->getArgument('private_key');

        if ($private_key === '' || $private_key === null)
        {
            return $languara->print_message('error_invalid_private_key', 'FAILURE');
        }
        
        echo PHP_EOL;
        $languara->print_message('notice_start_connect', 'SUCCESS');
        echo PHP_EOL;
        
        try
        {
            $languara->connect($private_key, $languara->platform);
        }
        catch (\Exception $ex) 
        {
            echo PHP_EOL;
            ($languara->print_message($ex->getMessage(), 'FAILURE'));
            echo PHP_EOL;
            return;
        }
        
        echo PHP_EOL;
        $languara->print_message('success_connected', 'SUCCESS');
        echo PHP_EOL . PHP_EOL;
    }
}