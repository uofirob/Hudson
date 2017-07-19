<?php

namespace app;

use DI;
use framework\command\CommandStrategyFactory;
use Config;

return [
    'ConquestChannel' => Config::$ConquestChannel,
    'IDataAccessAdapter' => DI\object('dal\DataAccessAdapter'),
    'CoreRepository' => DI\object('dal\managers\CoreRepository')
            ->constructor(DI\get('IDataAccessAdapter')),
    'ConquestRepository' => DI\object('dal\managers\ConquestRepository')
            ->constructor(DI\get('IDataAccessAdapter')),
    'ZoneRepository' => DI\object('dal\managers\ZoneRepository')
            ->constructor(DI\get('IDataAccessAdapter')),
    'NodeRepository' => DI\object('dal\managers\NodeRepository')
            ->constructor(DI\get('IDataAccessAdapter')),
    'StrikeRepository' => DI\object('dal\managers\StrikeRepository')
            ->constructor(DI\get('IDataAccessAdapter')),
    'UserRepository' => DI\object('dal\managers\UserRepository')
            ->constructor(DI\get('IDataAccessAdapter')),
    'ISlackApi' => DI\object('framework\slack\SlackApi'),
    'StatusCommandStrategy' => DI\object('framework\command\StatusCommandStrategy')
            ->constructor(DI\get('CoreRepository'), DI\get('ConquestRepository'), DI\get('ZoneRepository'), DI\get('StrikeRepository'), DI\get('ISlackApi')),
    'ConquestManager' => DI\object('framework\conquest\ConquestManager')
            ->constructor(DI\get('ConquestRepository'), DI\get('ZoneRepository'), DI\get('NodeRepository'), DI\get('StrikeRepository')),
    'ZoneManager' => DI\object('framework\conquest\ZoneManager')
            ->constructor(DI\get('ConquestRepository'), DI\get('ZoneRepository'), DI\get('StrikeRepository')),
    'framework\command\ICommandStrategy' => [
                DI\object('framework\command\InitCommandStrategy')
                ->constructor(DI\get('CoreRepository'), DI\get('ISlackApi')),
                DI\object('framework\command\StrikeCommandStrategy')
                ->constructor(DI\get('ConquestRepository'), DI\get('ZoneRepository'), DI\get('NodeRepository'), DI\get('StrikeRepository'), DI\get('ISlackApi'), DI\get('StatusCommandStrategy')),
        DI\get('StatusCommandStrategy'),
                DI\object('framework\command\NodeCallCommandStrategy')
                ->constructor(DI\get('ConquestRepository'), DI\get('ZoneRepository'), DI\get('NodeRepository'), DI\get('StrikeRepository'), DI\get('UserRepository'), DI\get('ISlackApi'), DI\get('StatusCommandStrategy')),
                DI\object('framework\command\HoldCommandStrategy')
                ->constructor(DI\get('ConquestRepository'), DI\get('ZoneRepository'), DI\get('NodeRepository'), DI\get('ISlackApi'), DI\get('StatusCommandStrategy')),
                DI\object('framework\command\ZoneCommandStrategy')
                ->constructor(DI\get('ConquestRepository'), DI\get('ZoneRepository'), DI\get('ISlackApi'), DI\get('StatusCommandStrategy')),
                DI\object('framework\command\ClearCommandStrategy')
                ->constructor(DI\get('ConquestRepository'), DI\get('ZoneRepository'), DI\get('NodeRepository'), DI\get('StrikeRepository'), DI\get('ISlackApi'), DI\get('StatusCommandStrategy')),
                DI\object('framework\command\CancelCommandStrategy')
                ->constructor(DI\get('ConquestRepository'), DI\get('ZoneRepository'), DI\get('ISlackApi'), DI\get('StatusCommandStrategy')),
                DI\object('framework\command\StatsCommandStrategy')
                ->constructor(DI\get('ConquestManager'), DI\get('ISlackApi')),
                DI\object('framework\command\SummaryCommandStrategy')
                ->constructor(DI\get('ConquestManager'), DI\get('ISlackApi')),
                DI\object('framework\command\LeadCommandStrategy')
                ->constructor(DI\get('ConquestRepository'), DI\get('UserRepository'), DI\get('ISlackApi'), DI\get('ConquestChannel')),
    ],
    'CommandStrategyFactory' => DI\factory(function($strategies)
    {
        return new CommandStrategyFactory($strategies);
    })->parameter('strategies', DI\get('framework\command\ICommandStrategy')),
];
