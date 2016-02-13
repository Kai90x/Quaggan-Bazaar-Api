<?php
/**
 * Created by PhpStorm.
 * User: ikraammoothianpillay1
 * Date: 8/15/15
 * Time: 6:35 PM
 */

namespace KaiApp\Controller;

use KaiApp\JsonTransformers\DungeonsTransformer;
use KaiApp\JsonTransformers\SimpleTransformer;
use KaiApp\RedBO\RedDungeons;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Slim\Http\Request;
use Slim\Http\Response;

class DungeonsController  extends BaseController
{
	private $redDungeons;

	public function __construct(RedDungeons $_redDungeons) {
		$this->redDungeons = $_redDungeons;
		parent::__construct();
	}

    public function refresh(Request $request,Response $response, array $args)
    {
		$this->redDungeons->wipe();
		
		$this->redDungeons->add("Ascalonian Catacombs","Story",7300,0);
		$this->redDungeons->add("Ascalonian Catacombs","Path 1 - Hodgins",18100,60);
		$this->redDungeons->add("Ascalonian Catacombs","Path 2 - Detha",18100,60);
		$this->redDungeons->add("Ascalonian Catacombs","Path 3 - Tzark",18100,60);
		
		
		$this->redDungeons->add("Caudecus's Manor","Story",7300,0);
		$this->redDungeons->add("Caudecus's Manor","Path 1 - Asura",13100,60);
		$this->redDungeons->add("Caudecus's Manor","Path 2 - Seraph",13100,60);
		$this->redDungeons->add("Caudecus's Manor","Path 3 - Butler",13100,60);
		
		$this->redDungeons->add("Twilight Arbor","Story",7300,0);
		$this->redDungeons->add("Twilight Arbor","Path 1 - Leurent (Up)",13100,60);
		$this->redDungeons->add("Twilight Arbor","Path 3 - Vevina (Forward)",13100,60);
		$this->redDungeons->add("Twilight Arbor","Path 4 - Aetherpath",13100,60);
		
		$this->redDungeons->add("Sorrow's Embrace","Story",7300,0);
		$this->redDungeons->add("Sorrow's Embrace","Path 1 - Fergg",13100,60);
		$this->redDungeons->add("Sorrow's Embrace","Path 2 - Rasolov",13100,60);
		$this->redDungeons->add("Sorrow's Embrace","Path 3 - Koptev",13100,60);
		
		$this->redDungeons->add("Citadel of Flame","Story",7300,0);
		$this->redDungeons->add("Citadel of Flame","Path 1 - Ferrah",13100,60);
		$this->redDungeons->add("Citadel of Flame","Path 2 - Magg",13100,60);
		$this->redDungeons->add("Citadel of Flame","Path 3 - Rhiannon",13100,60);
		
		$this->redDungeons->add("Honor of the Waves","Story",7300,0);
		$this->redDungeons->add("Honor of the Waves","Path 1 - Butcher",13100,60);
		$this->redDungeons->add("Honor of the Waves","Path 2 - Plunderer",13100,60);
		$this->redDungeons->add("Honor of the Waves","Path 3 - Zealot",13100,60);
		
		$this->redDungeons->add("Crucible of Eternity","Story",7300,0);
		$this->redDungeons->add("Crucible of Eternity","Path 1 - Submarine",13100,60);
		$this->redDungeons->add("Crucible of Eternity","Path 2 - Teleporter",13100,60);
		$this->redDungeons->add("Crucible of Eternity","Path 3 - Front door",13100,60);
		
		$this->redDungeons->add("The Ruined City of Arah","Story",7300,0);
		$this->redDungeons->add("The Ruined City of Arah","Path 1 - Jotun",35000,60);
		$this->redDungeons->add("The Ruined City of Arah","Path 2 - Mursaat",35000,60);
		$this->redDungeons->add("The Ruined City of Arah","Path 3 - Forgotten",15500,60);
		$this->redDungeons->add("The Ruined City of Arah","Path 4 - Seer",35000,60);
		
		$this->redDungeons->add("Fractals of the Mists","Level 1-10",10000,42);
		$this->redDungeons->add("Fractals of the Mists","Level 11-20",10000,50);
		$this->redDungeons->add("Fractals of the Mists","Level 21-30",10000,68);
		$this->redDungeons->add("Fractals of the Mists","Level 31-40",10000,81);
		$this->redDungeons->add("Fractals of the Mists","Level 41-50",10000,94);

		return $this->response(new Item("Dungeons have been reset",new SimpleTransformer()),$response);
    }

    public function all(Request $request,Response $response, array $args)
    {
        $dungeons = $this->redDungeons->getAll();

        return !empty($dungeons) ?  $this->response(new Collection($dungeons, new DungeonsTransformer()),$response)
		: $this->response(new Item("No dungeons found", new SimpleTransformer()),$response,404);
    }

}