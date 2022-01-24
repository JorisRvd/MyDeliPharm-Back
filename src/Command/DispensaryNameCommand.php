<?php

namespace App\Command;

use App\Repository\DispensaryRepository;
use App\Service\magosmApi;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class DispensaryNameCommand extends Command
{
    protected static $defaultName = 'app:dispensary:name';
    protected static $defaultDescription = 'Get dispensary names from omdbapi.com';

    // Nos services
    private $dispensaryRepository;
    private $entityManager;
    private $magosmApi;

    public function __construct(DispensaryRepository $dispensaryRepository, ManagerRegistry $doctrine, magosmApi $magosmApi)
    {
        $this->dispensaryRepository = $dispensaryRepository;
        $this->entityManager = $doctrine->getManager();
        $this->magosmApi = $magosmApi;

        // On appelle le constructeur parent
        parent::__construct();
    }

    protected function configure(): void
    {
        
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Permet un affichage trop stylé dans le terminal
        $io = new SymfonyStyle($input, $output);

        $io->info('Mise à jour des noms d\'officine');

        // Récupérer tous les films (via MovieRepository)
        $dispensary = $this->dispensaryRepository->findAll();
        // Pour chaque film
        foreach ($dispensary as $dispensarys) {

            $io->info($dispensarys->getName());

            // Récupérer le poster lié au titre du film
            $dispensaryName = $this->magosmApi->fetchName($dispensarys->getName());

            if (!$dispensaryName) {
                $io->warning('Nom non trouvé');
            }

            // On met à jour le poster du film
            $dispensarys->setName($dispensaryName);

        }
        // On flush (via l'entityManager)
        $this->entityManager->flush();

        $io->success('Les noms ont été mis à jour');

        return Command::SUCCESS;
    }
}
