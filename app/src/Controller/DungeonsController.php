<?php
/**
 * Created by PhpStorm.
 * User: ikraammoothianpillay1
 * Date: 8/15/15
 * Time: 6:35 PM
 */

namespace KaiApp\Controller;

use Utils\Common;
use RedBO\RedFactory;

class dungeons extends BaseController
{

    public function RefreshAction()
    {
		RedFactory::GetRedDungeons()->DeleteAll();
		
		RedFactory::GetRedDungeons()->AddDungeons("Ascalonian Catacombs","Story",7300,0);
		RedFactory::GetRedDungeons()->AddDungeons("Ascalonian Catacombs","Path 1 - Hodgins",18100,60);
		RedFactory::GetRedDungeons()->AddDungeons("Ascalonian Catacombs","Path 2 - Detha",18100,60);
		RedFactory::GetRedDungeons()->AddDungeons("Ascalonian Catacombs","Path 3 - Tzark",18100,60);
		
		
		RedFactory::GetRedDungeons()->AddDungeons("Caudecus's Manor","Story",7300,0);
		RedFactory::GetRedDungeons()->AddDungeons("Caudecus's Manor","Path 1 - Asura",13100,60);
		RedFactory::GetRedDungeons()->AddDungeons("Caudecus's Manor","Path 2 - Seraph",13100,60);
		RedFactory::GetRedDungeons()->AddDungeons("Caudecus's Manor","Path 3 - Butler",13100,60);
		
		RedFactory::GetRedDungeons()->AddDungeons("Twilight Arbor","Story",7300,0);
		RedFactory::GetRedDungeons()->AddDungeons("Twilight Arbor","Path 1 - Leurent (Up)",13100,60);
		RedFactory::GetRedDungeons()->AddDungeons("Twilight Arbor","Path 3 - Vevina (Forward)",13100,60);
		RedFactory::GetRedDungeons()->AddDungeons("Twilight Arbor","Path 4 - Aetherpath",13100,60);
		
		RedFactory::GetRedDungeons()->AddDungeons("Sorrow's Embrace","Story",7300,0);
		RedFactory::GetRedDungeons()->AddDungeons("Sorrow's Embrace","Path 1 - Fergg",13100,60);
		RedFactory::GetRedDungeons()->AddDungeons("Sorrow's Embrace","Path 2 - Rasolov",13100,60);
		RedFactory::GetRedDungeons()->AddDungeons("Sorrow's Embrace","Path 3 - Koptev",13100,60);
		
		RedFactory::GetRedDungeons()->AddDungeons("Citadel of Flame","Story",7300,0);
		RedFactory::GetRedDungeons()->AddDungeons("Citadel of Flame","Path 1 - Ferrah",13100,60);
		RedFactory::GetRedDungeons()->AddDungeons("Citadel of Flame","Path 2 - Magg",13100,60);
		RedFactory::GetRedDungeons()->AddDungeons("Citadel of Flame","Path 3 - Rhiannon",13100,60);
		
		RedFactory::GetRedDungeons()->AddDungeons("Honor of the Waves","Story",7300,0);
		RedFactory::GetRedDungeons()->AddDungeons("Honor of the Waves","Path 1 - Butcher",13100,60);
		RedFactory::GetRedDungeons()->AddDungeons("Honor of the Waves","Path 2 - Plunderer",13100,60);
		RedFactory::GetRedDungeons()->AddDungeons("Honor of the Waves","Path 3 - Zealot",13100,60);
		
		RedFactory::GetRedDungeons()->AddDungeons("Crucible of Eternity","Story",7300,0);
		RedFactory::GetRedDungeons()->AddDungeons("Crucible of Eternity","Path 1 - Submarine",13100,60);
		RedFactory::GetRedDungeons()->AddDungeons("Crucible of Eternity","Path 2 - Teleporter",13100,60);
		RedFactory::GetRedDungeons()->AddDungeons("Crucible of Eternity","Path 3 - Front door",13100,60);
		
		RedFactory::GetRedDungeons()->AddDungeons("The Ruined City of Arah","Story",7300,0);
		RedFactory::GetRedDungeons()->AddDungeons("The Ruined City of Arah","Path 1 - Jotun",35000,60);
		RedFactory::GetRedDungeons()->AddDungeons("The Ruined City of Arah","Path 2 - Mursaat",35000,60);
		RedFactory::GetRedDungeons()->AddDungeons("The Ruined City of Arah","Path 3 - Forgotten",15500,60);
		RedFactory::GetRedDungeons()->AddDungeons("The Ruined City of Arah","Path 4 - Seer",35000,60);
		
		RedFactory::GetRedDungeons()->AddDungeons("Fractals of the Mists","Level 1-10",10000,42);
		RedFactory::GetRedDungeons()->AddDungeons("Fractals of the Mists","Level 11-20",10000,50);
		RedFactory::GetRedDungeons()->AddDungeons("Fractals of the Mists","Level 21-30",10000,68);
		RedFactory::GetRedDungeons()->AddDungeons("Fractals of the Mists","Level 31-40",10000,81);
		RedFactory::GetRedDungeons()->AddDungeons("Fractals of the Mists","Level 41-50",10000,94);
		
		echo json_encode(Common::GenerateResponse(Common::STATUS_SUCCESS,"Dungeons updated"));
    
    }

    public function GetAllAction()
    {
        $dungeons = RedFactory::GetRedDungeons()->GetAllDungeons();

        if (!empty($dungeons) ) {
            echo json_encode(Common::GenerateResponse(Common::STATUS_SUCCESS,Common::ConvertBeanToArray($dungeons, "dungeons")));
        } else {
            echo json_encode(Common::GenerateResponse(Common::STATUS_NOTFOUND,"No dungeons found"));
        }
    }

}