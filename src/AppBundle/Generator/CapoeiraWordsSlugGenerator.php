<?php
namespace AppBundle\Generator;

use AppBundle\Command\CreateWorkshop;
use AppBundle\Command\UpdateWorkshop;
use AppBundle\Command\WorkshopCommandInterface;
use AppBundle\Entity\Workshop;

class CapoeiraWordsSlugGenerator
{
    private $words = [
        "capoeira",
        "berimbau",
        "meia-lua",
        "negativa",
        "pandeiro",
        "rabo-de-arraia",
        "boca-de-peixe",
        "brazil",
        "parana",
        "mestre",
        "bimba",
        "pastinha",
        "portuguese",
        "morena",
        "reco-reco",
        "agogo",
        "waldemar",
        "bencao",
        "mortal",
        "folha-seca",
        "mariposa",
        "chutado",
        "parafuso",
        "tornado",
        "godeme",
        "cotovelhada",
        "joelhada",
        "role",
        "cocorinha",
        "crucifix",
        "bahia",
        "samba",
        "abada",
        "camisa",
        "cordao",
        "dobrao",
        "besouro",
        "malandro",
        "au",
        "benguela",
        "regional",
        "angola",
        "amazonas",
        "cavalaria",
        "sao-bento",
        "pequeno",
        "maria",
        "bonita",
        "manjado",
        "guerreiro",
        "biscoitinho",
        "biscoito",
        "formiga",
        "relampago",
        "real",
        "fusca",
        "bloco",
        "timbalada",
        "revelacao",
        "aragao",
        "jorge",
        "banda-eva",
        "marrisa",
        "monte",
        "amigo",
        "faisca",
        "gota",
        "boneca",
        "marreta",
        "uirapuru",
        "ligeiro",
        "veneno",
        "alemao",
        "nordeste",
        "nordestinho"
    ];

    private $workshopRepository;

    /**
     * WorkshopSlugGenerator constructor.
     * @param $workshopRepository
     */
    public function __construct($workshopRepository)
    {
        $this->workshopRepository = $workshopRepository;
    }

    /**
     * @param Workshop|WorkshopCommandInterface $workshop
     * @return string
     */
    public function generate($workshop){
        $i = 1;

        do {
            $params = $this->getMixedWords();
            $slug = implode("-", $params);
        } while (!is_null($this->workshopRepository->findOneBySlug($slug)));

        return $slug;
    }

    private function getMixedWords()
    {
        $words = $this->words;
        shuffle($words);
        return array_slice($words, 0, 5);
    }
}