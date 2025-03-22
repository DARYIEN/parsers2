<?php
class Options{
    public $cities_list = array(
        1 => array('moscow' => 'Москва'),
        2 => array('spb' => 'Санкт-Петергбург'),
        3 => array('voronezh' => 'Воронеж'),
        4 => array('eburg' => 'Екатеринбург'),
        5 => array('kazan' => 'Казань'),
        6 => array('lipetsk' => 'Липецк'),
        7 => array('nizhniy_nov' => 'Нижний Новгород'),
        8 => array('nsk' => 'Новосибирск'),
        9 => array('rostovnd' => 'Ростов-на-Дону'),
        10 => array('ryazan' => 'Рязань'),
        11 => array('tver' => 'Тверь'),
        12 => array('vladimir' => 'Владимир'),
        13 => array('nazran' => 'Назрань'),
        14 => array('gudermes' => 'Гудермес'),
        15 => array('oskol' => 'Старый-Оскол'),
        16 => array('belgorod' => 'Белгород'),
        17 => array('cherep' => 'Череповец'),
        18 => array('posad' => 'Сергиев-Посад'),
        19 => array('electrougli' => 'ЕлектроУгли'),
        20 => array('chexov' => 'Чехов'),
        21 => array('kursk' => 'Курск'),
        22 => array('orel' => 'Орёл'),
        23 => array('bryansk' => 'Брянск'),
        24 => array('maxachkala' => 'Махачкала'),
        25 => array('nalchik' => 'Нальчик'),
        26 => array('kaluga' => 'Калуга'),
        27 => array('tula' => 'Тула'),
        28 => array('saransk' => 'Саранск'),
        29 => array('cheboksary' => 'Чебоксары'),
        30 => array('samara' => 'Самара'),
        31 => array('pyatigor' => 'Пятигорск'),
        32 => array('volgograd' => 'Волгоград'),
        33 => array('tambov' => 'Тамбов'),
        34 => array('novocherka' => 'Новочеркаск'),
        35 => array('krasnodar' => 'Краснодарск'),
        36 => array('ulyanovsk' => 'Ульяновск'),
        37 => array('saratov' => 'Саратов'),
        38 => array('penza' => 'Пенза'),
        39 => array('syzran' => 'Сызрань'),
        40 => array('titarovka' => 'Новотитаровская'),
        41 => array('stavropol' => 'Ставрополь'),
        42 => array('vladikavkaz' => 'Владикавказ'),
        43 => array('smolensk' => 'Смоленск'),
        44 => array('taganrok' => 'Таганрок'),
        45 => array('barnaul' => 'Барнаул'),
        46 => array('habarovsk' => 'Хабаровск'),
        47 => array('yaros' => 'Ярославль'),
        48 => array('minsk' => 'Минск'),
        49 => array('lobnay' => 'Лобня'),
        50 => array('krasnoyarks' => 'Красноярск'),
        51 => array('sochi' => 'Сочи'),
        52 => array('staryoskol' => 'Старый оскол'),
        53 => array('chelyabinsk' => 'Челябинск'),
        54 => array('ufa' => 'Уфа'),
        55 => array('astrakhan' => 'Астрахань'),
        56 => array('vologda' => 'Вологда'),
        57 => array('vladivostok' => 'Владивосток'),
        58 => array('nyzhny_tagil' => 'Нижний Тагил'),
        59 => array('ijevsk' => 'Ижевск'),
        60 => array('perm' => 'Пермь'),
        61 => array('orenburg' => 'Оренбург'),
        62 => array('nijnevartovsk' => 'Нижневартовск'),
        63 => array('magnitogorsk' => 'Магнитогорск'),
        64 => array('irkutsk' => 'Иркутск'),
        65 => array('arhangelsk' => 'Архангельск'),
        66 => array('jeleznodorojny' => 'Железнодорожный'),
        67 => array('almata' => 'Алмата'),
        68 => array('aktau' => 'Актау'),
        69 => array('atirau' => 'Атырау'),
        70 => array('taraz' => 'Тараз'),
        71 => array('astana' => 'Астана'),
        72 => array('pavlodar' => 'Павлодар'),
        73 => array('shimkent' => 'Шимкент'),
        74 => array('aktubinsk' => 'Актюбинск'),
        75 => array('kiev' => 'Киев'),
        76 => array('uralsk' => 'Уральск'),
        77 => array('omsk' => 'Омск'),
        78 => array('surgut' => 'Сургут'),
        79 => array('tumen' => 'Тумень'),
        80 => array('nchelny' => 'Набережные Челны'),
        81 => array('kemerovo' => 'Кемерово'),
        82 => array('novokuznetsk' => 'Новокузнецк'),
        83 => array('tomsk' => 'Томск'),
        84 => array('tumen' => 'Тюмень'),
        85 => array('velikiinovgorod' => 'Великий Новгород'),
        86 => array('toljatti' => 'Тольятти'),
        87 => array('kirov' => 'Киров'),
        88 => array('karaganda' => 'Караганда'),
        89 => array('yakutsk' => 'Якутск'),
        90 => array('yuzhno_sahalinsk' => 'Южно-Сахалинск')
    );
    public $list_parsers = array(
        1 => array(
            'CParKirpichGazobetonMoscow',
            'CParKirpichMoscow',
            'CParTopHouseMoscow',
            'CParMosKeramMoscow',
            'CParPulsStroyMoscow',
            'CParBlockStockMoscow',
            'CParSabonSMMoscow',
            'CParGrandLineMoscow',
            'CParAGMMoscow',
            'CParAgt',
            'CParSPgroup',
            'CParAtomSteel',
            'CParConsTaliNew',
            'CParNovaMetCom',
            'CParMetallSnabMoscow',
            'CParArielMet',
            'CParTruboMashServisMoscow',
            'CParApexMet',
            'CParFoss',
            'CParRTZMoscow',
            'CParNewKtz',
            'CParMhs',
            'CParStalintex',
            'CParTaider',
            'CParBrokinvestMoscow',
            //'CParNewTroyka',
            'CParTroykaNewNew',
            'CParMetallotorgMoscow',
            'CParDipos',
            //'CParSpecTechMash',
            'CParStroyReserv',
            'CParRgmkCR',
            'CParCParSKTK',
            'CParTdb',
            'CParMMDTg',
            'CParMetagor',
            'CParTitanMK',
            'CParAlmetallMoscow',
            'CParTechPromPost',
            'CParAgruppMetalloprokat',//
            'CParStroyStall',//
            'CParOmegaMetall',//
            'CParCentrStal',//
            'CParMosglavsnabMetall',//
            'CParMELService',//
            'CParSpecStall',//
            'CParRusmetprom',//
            'CParPartnerM',//
            'CParDemir',//
            'CParArtMetall',
            'CParMTK',
            'CParGelios',
            'CParTransNijStroy',
            'CParTehnokom',
            'CParMMtK',
            //'CParEkipMetall',//циклические ссылки
            'CParGlavMetResurs',
            'CParCParRapid',
            'CParMIG',
            'CParIronSnab',
            'CParImperialStroy',
            'CParStalSplav',
            'CParMetalloTehSnab',
            'CParStroyMetall',
            'CParInoxStal',
            'CParTyssenKruppMoscow',
            'CParSamorez',
            'CParProfProkat',
            'CParMetallMarket',
            'CParMetallTransStroy',
            'CParAstor',
            'CParA1PetrolPip',
            'CParProfMetComolectWeb',
            'CParMSetka',
            'CParSpecGpupp',
            'CParTeploVodSnab',
            'CParZitar',
            'CParCarMet',
            'CParElecmet',
            'CParTdstm',
            //'CParStroyOpt', //тройка
            'CParTrubNik',
            'CParSanTehComplect',
            'CParMedexEnergo',
            //'CParRealMetiz',//Стройка
            //'CParStroyMaterialy',//Стройка
            'CParPiloTorg',//Стройка
            //'CParMosKeram', //стройка
            'CParTDStal',
            'CParPegasM',
            //'CParRosatom',//стройка
            'CParIsolux',//стройка
            //'CParMosVodProm',//стройка
            'CParJeleznyFelix',
            //'CParKJBK',//стройка
            //'CParDorPlit',//стройка
            //'CParTigiKnauf',//стройка
            //'CParKirpichRu',//стройка
            //'CParGlavStroyKomplekt',//стройка
            //'CParTorgstroymat',//стройка
            //'CParShopvira',//стройка
            //'CParPPKEtalon',//стройка
            'CParMetalStroyRegion',
            'CParPromstroymetal',
            'CParPassat',
            //'CParStroyZhelezoBeton',//стройка
            //'CParJbi4',//стройка
            //'CParJbi1',//стройка
            //'CParOblCeram',//стройка
            //'CParMerkuryStroy',//стройка
            //'CParPasTermo',//стройка
            //'CParCosmoplast',//стройка
            //'CParPervoyaOcheredStroitelstva',//стройка
            'CParSPMetiz',
            //'CParJBIDostavka',//стройка
            //'CParTrestJBI',//стройка
            //'CParPromJBIKomplect',//стройка
            //'CParJBI4Zavod',//стройка
            'CParJBIKomplekt',//стройка
            'CParInjPromJelezoBeton',//стройка
            'CParMegaTorg',//стройка
            //'CParOKZ',//стройка
            'CParExpressMetall',
            'CParBotiGaika',
            //'CParBKArmatura', //нет ид и не работает
            'CParABSAlians',
            'CParOmerta',
            'CParPromFitArmatura',
            //'CParSotis',
            'CParPinInstrument',
            'CParTehArmatura',
            'CParJakko',
            'CParFitingTehcomplect',
            'CParStroyPost',
            'CParTrubPlastSnab',
            'CParInen',
            'CParSanTexOptTorg',
            'CParCapInvest',
            'CParStroySet',
            'CParMetinvestEuraz',
            'CParTrubTorg',
            'CParKanatStdMoscow',
            'CParMetalTorg',
            'CParMetalloff',
            'CParMetallov',
            'CParTsComplect',
            'CParNordSteel',
            'CParLars',
            'CParSaverhot',
            'CParMetKompMsk',
            'CParInvestGroup',
            'CParPrommet',
            'CParSksGkl',
            'CParStalInteh',
            'CParBologovskyArmaturnyZavod',
            'CParSanTehAssortiment',
            //'CParUniversalParser',
            'CParBmzmMoscow',
            'CParPrommet',
            'CParSksGkl',
            'CParAluminPro',
            'CParStalprom',
            'CParChermettorg',
            //'CParCityMetall',
            //'CParGlavMetall',
            'CParAtlant',
            'CParBoltRu',
            'CParUralMetallEnergoMoscow',
            'CParEstMsk',
            'CParGlavSnab',
            'CParGlavSnabStalintex',
            'CParMetPromIndustriya',
            'CParAmkResurs',
            'CParStalProMoscow',
            'CParMelallEnergiya',
            'CParInjenernieSeti',
            'CParSteelTrust',
            'CParInfrastel',
            'CParInter',
            'CParStalintexTreid',
            'CParZmkGost',
            'CParMetaltorgMetalBaza',
            'CParStalnoyDom',
            'CParMetProfil',
            'CParMosMetal',
            'CParDaikan',
            //'CParStroysnabF',
            'CParMetalGrup',
            'CParMetalB2B',
            'CPar1000met',
            'CParDoorStal',
            'CParMetPostavkaAgrupp',
            'CParBrick24',
            'CParAllMetGroup',
            'CParNbkPipe',
            'CParStalGroup',
            'CParLzm',
            'CParApriumNsk',
            'CParJBIStm',
            'CParAlfaPlus',
            'CParKBKBeton',
            'CParStroim54',
            'CParVistMetall',
            'CParRozmet',
            'CParArmtorg',
            'CParOptima',
            'CParArtMetallNew',
            'CParNTPZ',
            'CParUniYml',
            'CParUniYmlND',
            'CParUniYmlEvraz',
            'CParMEtOptTrade',
            'CParMetalloprokat',
            'CParProMetiz',
            'CParStroyKart',
            'CParBetonMix24',
            'CParLesnoyMag',
            'CParAvtoBeton',
            'CParEnergomonolit',
            'CParRabotiagiStroy',
            'CParSanTexBaza',
            'CParEgoIng',
            'CParDarStroy',
            'CParMosPromBeton',
            'CParBelBeton',
            'CParBrigadaSK',
            'CParTDJbi',
            'CParStalVelding',
            'CParAfinaPallada',
            'CParNpz',
            'CParConstructorNew',
            'CParMTKFortuna',
            'CParGIKMsc',
            'CParTrubResh',
            'CParDemidov',
            'CParMosGlavSnab',
            'CParDiposProcent',
            'CParDiposTula',
            'CParAgrupp2pr',
            'CParMCPlus10',
            'CParMCPlus20',
            'CParMCPlus3',
            'CParlsst',

        ),
        2 => array(
            'CParMosKeramSpb',
            'CParAGMSpb',
            'CParMetSouz',
            'CParBaltMetall',
            'CParMetallobaza4',
            'CParBaltTrade',
            'CParMetallGroupSZ',
            'CParCondorPlus',
            'CParBetonTesla',
            'CParMetallTskVoshod',
            'CParSteelUnion',
            'CParWestMet',
            'CParMtkSteel',
            'CParSpbSpk',
            'CParBrokinvestSpb',
            'CParMetallHolding',
            'CParAgruppMetalloprokatSpb',//
            'CParMetallotorgSpb',
            'CParAviaSteel',
            'CParAlmetallSpb',
            'CParLenSpecSteel',
            'CParUralMet',
            'CParAdamantSteel',
            'CParInterSteel',
            'CParAbtSteel',
            'CParNeotech',//
            'CParRosmetal',//
            'CParSank',//
            'CParSevZapMetall',//
            'CParLenStroy',
            'CParThyssenKrupp',
            'CParCityMet',
            'CPar1Metallobaza',
            'CParDiposSpb',
            'CParMetallocentr',
            'CParIjorskayaTrubnayaKompaniya',
            'CParGrandmetall',
            'CParMetexGrup',
            'CParStalSplavSpb',
            'CParSZMK',
            'CParNevMet',
            'CParPassatSPB',
            //'CParJBIProm',//стройка
            'CParSteelMax',
            'CParTransMet',
            'CParMetallProm',
            'CParMetInvestGrup',
            'CParKanatStd',
            'CParKonkordMetall',
            'CParNevaInvest',
            'CParInstrumentalnieStali',
            'CParMetalInvestSeveroZapad',
            'CParMetallosfera',
            'CParMetallocentrSpb',
            'CParElectrovenik',
            'CParStalnayaKompaniya',
            'CParNordMetall',
            'CParMetalurgicheskayaKopaniya',
            'CParAliansMetall',
            'CParStalSpb',
            'CParMetalProekt',
            'CParAlbatros',
            'CParMarkaStali',
            'CParMetizi',
            'CParTechKrep',
            'CParAnkerKrepej',
            'CParMTKSPB',
            'CParBmzmSpb',
            'CParLenStalinvest',
            'CParMetiz',
            'CParOptKrepejh',
            'CParContact',
            'CParStalProSPB',
            'CParInjenernieSetiSPB',
            'CParProfStroyMetall',
            'CParSevernyBereg',
            'CParPervayaStroytelnayaBaza',
        ),
        3 => array(
            'CParMosKeramVoronezh',
            'CParMetallotorgVoronezh',
            'CParAlmetallVoronezh',
            'CParStelMetVor',
            'CParKrovlyaIZabor',
            'CParSanTexOptTorgVoronej',
            'CParKBGrupp',
            'CParStalProVoronej',
        ),
        4 => array(
            'CParGrandLineEkb',
            'CParAGMEkb',
            'CParMetallGazSnab',
            'CParTRGroup',
            'CParAlmetallEburg',
            'CParStaliUrala',
            'CParUralMostMetall',//
            'CParEVRASEkaterinburg',//
            'CParArtStalUral',//
            'CParGermes',//
            'CParRosGrup',//
            'CParPartnerTreid',//
            'CParVector',
            'CParTruboMashServisEburg',
            'CParPassatEkb',
            'CParMetallLogika',
            'CParStaliningrad',
            'CParRegCM',
            'CParBvbAlience',
            'CParFSKTobol',
            //'CParSantehComplectUral',
            'CParUralStalInvest',
            'CParMetallSnabEkb',
            'CParTrubProm',
            'CParStalMash',
            'CParGkStal',
            'CParSaverhotEkb',
            'CParStalInProfil',
            'CParStalComplect',
            'CParUrZSAS',
            'CParComplectStroy',
            'CParMetallOptTorg',
            'CParIPKuzminihJuliyaPavlovna',
            'CParRegionalnyCenterMetalloprokata',
            'CParMetallGrupp',
            'CParPromSnabMetallService',
            'CParMetpromko',
            'CParUralMetallEnergoEBurg',
            'CParMetalobazaUral',
            'CParTGserv'

        ),
        5 => array(
            //'CPar23metKazan',
            'CParMetallotorgKazan',
            'CParRTZKazan',
            'CParMetaslav',
            'CParLifeMed',
            'CParAlmetall',
            'CParMkaRegion',
            'CParBazisStan',
            'CParDomStroyKazan',
            'CParIntegralS',
            'CParBazisMetallKazan',
            'CParAsconaKazan',
            'CParMTKKazan',
            'CParSPKKazan',
            'CParUralMetallEnergoKazan',
        ),
        6 => array(
            'CParMosKeramLipetsk',
            //'CPar23metLipetsk',
            'CParBrokinvestLipetsk',
            'CParMetallotorgLipetsk',
            'CParLipMetall',
            'CParMetalloIndustria',
        ),
        7 => array(
            'CParMosKeramNn',
            'CParAGMNn',
            //'CPar23metNizhniyNov',
            'CParMetallotorgNizhniNovgorod',
            'CParStalinn',
            'CParMetallStroyGroup',
            'CParRTZNnovgorod',
            'CParKizimov',
            'CParShery',
            'CParMTKNovgorod',
            'CParMetallocomplectMNN',
            'CParStalSplavNN',
            'CParStalSplavNN',
            //'CParMcNn',
            'CParMetalComplectServis',
            'CParMelallservisNN',
            'CParUSMetall',
            'CParIronGrupp',
            'CParStaleprocatNN',
            'CParSBSMetall',
            'CParNizegorodskyOpitnoExpZavod',
            'CParUralMetallEnergoNN',
            'CParStalProNN',
            'CParInjenernieSetiNN',
        ),
        8 => array(
            'CParAGMNsk',
            //'CPar23metNsk',
            'CParRTZNovosibirsk',
            'CParLegirovanieStali',
            'CParFerrum',
            'CParElefant',
            'CParMetalComplectSnab',
            'CParSibMetall',//
            'CParSibMetSnab',
            'CParAromaOpt',
            'CParAlyanceMGNsk',
            'CParSteelCityNsk',
            'CParOmmetNsk',
            'CParTdNovosib',
            'CParMegaton',
            //'CParSipost',
            'CParRosCherMet',
            'CParTdMetalSib',
            'CParSPKNovosibirsk',
            'CParZhbi12',
            'CParZhbi42',
            //'CParKastor',
        ),
        9 => array(
            'CParMosKeramRostov',
            'CparAGMRostov',
            //'CPar23metRostovND',
            'CParMetallotorgRostovND',
            'CParUmcRn',
            //'CParSteelProm',
            'CParDonStal',
            'CParPromIS',
            'CParMetAktive',
            'CParSTCompany',
            'CParRostovTehOptTorg',
            'CParAvangardRostov',
            'CParAplhaUg',
            'CParSpecStalPro',
            'CParMetallTransGruz',
            'CParAvangardMetall',
            'CParStalPromRostov',
            //'CParUglerod',
            //'CParMcRostov',
            'CParStalProRostov',
        ),
        10 => array(
            'CParKirpichGazobetonRyazan',
            'CParMosKeramRyazan',
            'CParBrokinvestRyazan',
            'CParRTZRyazan',
            'CParAlmaSTRyazan',
        ),
        11 => array(
            'CParKirpichGazobetonTver',
            'CParBrokinvestTver',
            'CParMetallotorgTver',
            'CParRTZTver',
            'CParInjenernieSetiTver',
        ),
        12 => array(
            'CParKirpichGazobetonVladimir',
            'CParBrokinvestVladimir',
            'CParMetallotorgVladimir',
            'CParAlmetallVladimir',
            'CParMetallHoldingVladimir',
            'CParMuromArmSnab',
            'CParSanTexOptTorgVladimir',
            'CParTeploVodSnabVladimir',
            'CParStalTorg',
            'CParVladresurs',
            'CParInjenernieSetiVladimir',
        ),
        13 => array(
            'CParMetallotorgNazran'
        ),
        14 => array(
            'CParMetallotorgGudermes'
        ),
        15 => array(
            'CParMetallotorgOskol'
        ),
        16 => array(
            'CParMetallotorgBelgorod',
            'CParAlmetallBelgorod',
            'CParTruboMashServisBel',
            //'CParMcBelgorod',
            'CParStalProBelgorod',
        ),
        17 => array(
            'CParMetallotorgCherep'
        ),
        18 => array(
            'CParMetallotorgPosad',
            'CParBrokinvestSergievPasad',
            'CParLarsSergiev',
        ),
        19 => array(
            'CParMetallotorgElectroUgli',
            'CParLarsElectroUgli',
        ),
        20 => array(
            'CParMetallotorgChexov',
            'CParBrokinvestChexov',
            'CParLarsChexov',
        ),
        21 => array(
            'CParMetallotorgKoyrsk',
            'CParStalProKursk',
            //'CParMcKursk'
        ),
        22 => array(
            'CParKirpichGazobetonOrel',
            'CParAGMOrel',
            'CParMetallotorgOrel'
        ),
        23 => array(
            'CParAGMBryansk',
            'CParMetallotorgBryansk',
            'CParAlmetallBryansk',
            'CParStalProBryansk',
            //'CParMcBryansk',
        ),
        24 => array(
            'CParMetallotorgMaxachkala',
            'CParUmcMaxachkala'
        ),
        25 => array(
            'CParMetallotorgNalchik'
        ),
        26 => array(
            'CParKirpichGazobetonKaluga',
            'CParMosKeramKaluga',
            'CParMetallotorgKaluga',
            'CParAlmetallKaluga',
            'CParMetallGroupRus',
            'CParStalProAssKaluga',
            'CParInjenernieSetiKaluga',
        ),
        27 => array(
            'CParKirpichGazobetonTula',
            'CParMosKeramTula',
            'CParMetallotorgTula',
            'CParBrokinvestTula',
            'CParMhsTula',
            'CParGefest',
            'CParRTZTula',
            'CParStalProTula',
            'CParInjenernieSetiTula',
        ),
        28 => array(
            'CParMetallotorgSaransk',
            'CParStalProSaransk',
        ),
        29 => array(
            'CParMetallotorgCheboksary'
        ),
        30 => array(
            'CParMosKeramSamara',
            'CParMetallotorgSamara',
            'CParBazisMetall',
            'CParRTZSamara',
            'CParTorgMet',
            'CParAlmaST',
            'CParMTKSamara',
            'CParStalSplavSamara',
            'CParMetTehnoN',
            'CParAcronResurs',
            'CParOptSam',
            'CParMetaPlex',
            'CParTDMMK',
            'CParStalGroop',
            //'CParMcSamara',
            'CParVolgaStall',
            'CParSPKSamara',
            'CParUralMetallEnergoSamara',
            'CParStalProSamara',
            'CParInjenernieSetiSamara',
            'CParMetalLada',
        ),
        31 => array(
            'CParMetallotorgPyatigor',
            'CParStalProAssPyatigorsk',
        ),
        32 => array(
            'CParMetallotorgVolgograd',
            'CParAlmetallVolgograd',
            'CParVolgogradSpecMetall',
            'CParMetallPlus',
            'CParVoljskyMetall',
            'CParGosTorg',
            'CParStroyMag',
            'CParRosTrubStal',
            'CParVolgoStroyMetall',
            'CParTehGrup',
            'CParMirSetki',
            'CParSoyzStal',
            'CParMTKVolgograd',
            'CParStalProVolgograd',
        ),
        33 => array(
            'CParMosKeramTambov',
            'CParMetallotorgTambov',
            'CParInjenernieSetiTambov',
        ),
        34 => array(
            'CParMetallotorgNovocherka',
            'CParUmcNovocher',
        ),
        35 => array(
            'CParMosKeramKrasnodar',
            'CParAGMKrd',
            'CParMetallotorgKrasnodar',
            'CParDiposKuban',
            'CParUmcKrasnodar',
            //'CPar23metKrasnodar',
            'CParAlmetallKrasnodar',
            'CParPromISKrasnodar',
            //'CParMcKrasnodar',
            'CParPassatKrasnodar',
            'CParSanTexOptTorgKrasnodar',
            'CParTrubTorgKrasnodar',
            'CParKanatStdKrasnodar',
            'CParMTKKrasnodar',
            'CParMetIndust', //нет id
            'CParStalServis', //нет id
            'CParRusMetKrasnodar', //нет id
            'CParMSServis', //нет id
            'CParMetKomp', //нет id
            'CParUgKomplect', //нет id
            'CParSPKKrasnodar',
            'CParStalProKrasnodar',
        ),
        36 => array(
            'CParMetallotorgUlyanovsk',
            'CParSanTexOptTorgUljanovsk',
        ),
        37 => array(
            'CParMetallotorgSaratov',
            //'CParMcBalakovo',
            'CParMTKSaratov',
            'CParStalProSaratov',
            'CParInjenernieSetiSaratov',
        ),
        38 => array(
            'CParMosKeramPenza',
            'CParMetallotorgPenza',
            'CParRTZPenza',
            //'CParMcPenza',
            'CParOmegaArm',
            'CParStalProPenza',
        ),
        39 => array(
            'CParMetallotorgSyzran'
        ),
        40 => array(
            'CParMetallotorgTitarovka'
        ),
        41 => array(
            'CParMetallotorgStavropoli',
            'CParUmcStavropol',
            'CParPromISStavropol',
            'CParStalStavropol',
        ),
        42 => array(
            'CParMetallotorgVladikavkaz'
        ),
        43 => array(
            'CparAGMSmolensk',
            //'CParMcSmolensk'
            'CParSmolMetTorg',
            'CParStalProSmolensk',
        ),
        44 => array(
            'CParPromISTaganrok',
            'CParStalMet',
            //'CParMcTaganrok'
            'CParMetallocentrTaganrog',
        ),
        45 => array(
            'CParAGMBarnaul',
            'CParAlmetallBarnaul',
            'CParSteelSiberia',
            'CParPromEx',
            'CParDiposAltai',
            //'CParMcBarnaul'
        ),
        46 => array(
            'CParAGMHabarovsk',
            'CParAlmetallHabarovsk',
            'CParDantaDv',
            'CParDmk',
            //'CParMcHabarovsk'
        ),
        47 => array(
            'CParMosKeramYaroslavl',
            'CParBrokinvestYaros',
            'CParNewKtzYaros',
            'CParStalMetYaros',
            'CParMechelYar',
        ),
        48 => array(
            'CParMhsMinsk'
        ),
        49 => array(
            'CParMetallotorgLobnay',
            'CParRTZLobnya',
            'CParLarsLobnya',
        ),
        50 => array(
            'CParAGMKrasnoyarsk',
            'CParSibMet',
            'CParMetalloTorgKrasnoyarsk',
            'CParKrasMet',
            'CParSibTorg',
            //'CParUmcKrasnoyarks'
            //'CParUmcKrasnoyarks',
            'CParAAALiderMetall',
            'CParSPKKrasnoyarsk',
        ),
        51 => array(
            'CParUmcSochi',
            'CParAlmetallSochi',
            'CParSPKSochi',
        ),
        52 => array(
            'CParEvrosteel'//
        ),
        53 => array(
            'CParAGMChelyabinsk',
            'CParRTZChelyabinsk',
            'CParChelyabMetallUralOptTorg',
            'CParMetallotyorgChelyab',
            'CParSouzMetallChelyab',
            'CParSnabPlusChelyab',
            'CParEVRAS',//
            'CParTorgTeh',
            'CParCnsk',
            'CParStalnayaManufactura',
            'CParRealMetiz',
            //'CParSantehComplectChelab',
            'CParTrubnieSistemi',
            //'CParAresSamorez', //стройка
            'CParSPKChelyabinsk',
            'CParArmroscomplect',
            'CParSantehArmatura',
            'CParInMet',
            'CParInterBazis',
            'CParSunPipeSesvice',
            'CParSpecStal',
            'CParTDUralstal',
            'CParAiss',
            'CParKaur',
            'CParUralTehProm',
            'CParChelabsnabmetall',
            'CParCHEMTK',
            'CParPorta',
            'CParTerritoriaStroyitelnogoKrepezha',
            'CParUrZSASChelyab',
            'CParUralMetallEnergo',
        ),
        54 => array(
            'CParAGMUfa',
            'CParRTZUfa',
            'CParAlmetallUfa',
            'CParRosMetall',
            'CParBashMet',
            'CParTKBSD',
            'CParEVRASUfa',//
            'CParTrubTorgUfa',
            'CParBvbAlienceUfa',
            'CParUralMetallEnergoUfa',
        ),
        55 => array(
            'CParAlmetallAstrahan',
            'CParMetallocentrAstrahan',
            'CParSPKAstrahan',
            'CParStalProAssTrahan',
        ),
        56 => array(
            'CParAlmetallVologda',
            'CParStalProVologda',
        ),
        57 => array(
            'CParAGMVladivostok',
            'CParAlmetallVladivostok',
            'CParSteelDV',
            'CParVostokInvestStal',//
            'CParMetallSnabVlad',//
        ),
        58 => array(
            'CParUZSB',
            //'CParSantehComplectTagil',
            'CParUralMetallEnergoTagil',
        ),
        59 => array(
            'CParEVRASIjevsk',//
            'CParStalProIjevsk',
        ),
        60 => array(
            'CParEVRASPerm',//
            'CParMTKPerm',//
            'CParUralMetallEnergoPerm',
            'CParStalProAssPerm',
        ),
        61 => array(
            'CParEVRASOrenburg',//
            'CParSPKOrenburg',
            'CParUralMetallEnergoOrenburg',
        ),
        62 => array(
            'CParEVRASNijnivartovsk',//
        ),
        63 => array(
            'CParEVRASMagnitogorsk',//
            'CParSferaM',
            'CParUralMetallEnergoMagnitogorsk',
        ),
        64 => array(
            'CParVSMB',//
            'CParOmmetIrk',//
            'CParBaikalit',//
            'CParTemerso',//
            'CParIriyMet',//
            'CParSibMetallComl',//
        ),
        65 => array(
            'CParSevelStalArhangelsk',
        ),
        66 => array(
            'CParImperialStroyJelDor',
            'CParCarMetJelDor',
            'CParLarsZhelezka',
            'CParArtMetallJD',
        ),
        67 => array(
            'CParMetalloSklad',
            'CParKazTemirKontraktAlmaty',
            'CParMechelKazahstan',
            'CParStalKomercGrupp',
            'CParRossProcat',
            'CParIronCommerceCompanyAlmata',
            'CParLiderMetallAlmati',
        ),
        68 => array(
            'CParIronCommerceCompanyAktau',
            'CParLiderMetallAktau',
            'CParKazTemirKontraktAktau',
        ),
        69 => array(
            'CParIronCommerceCompanyAtirau',
            'CParKazTemirKontraktAtirau',
        ),
        70 => array(
            'CParLiderMetallTaraz',
        ),
        71 => array(
            'CParKazpromAstana',
            'CParKazRosStroy',
            'CParKazTemirKontraktAstana',
        ),
        72 => array(
            'CParKazpromPavlodar',
        ),
        73 => array(
            'CParKazpromShimkent',
        ),
        74 => array(
            'CParKazpromAktyubinsk',
            'CParKazTemirKontraktAktobe',
        ),
        75 => array(
            'CParInterBud',
            'CParAjaxCompany',
            'CParMetallHoldingKiev',
            'CParUkspar',
            'CParMetinvestKiev',
        ),
        76 => array(
            'CParKazTemirKontraktUralsk',
        ),
        77 => array(
            'CParOmskSpk',
            'CParOmmetOmsk',
            'CParOmskyElectronnyZavod',
            'CParPromtehcomplect',
        ),
        78 => array(
            'CParBvbAlienceSurgut',
            //'CParSantehComplectSurgut',
        ),
        79 => array(
            'CParBvbAlienceTumen',
        ),
        80 => array(
            'CParIntegralSChelny',
            'CParUralMetallEnergoNabChelny',
        ),
        81 => array(
            'CParKemService',
            'CParTransLine',
        ),
        82 => array(
            'CParAGMNvk',
            'CParVestatradenk',
            'CParCombaer',
        ),
        83 => array(
            //'CParTomTrade',
            'CParSPKTomsk',
        ),
        84 => array(
            // 'CParSantehComplectTumen',
            'CParUralMetallEnergoTumen',
        ),
        85 => array(
            'CParSevMetallSnab',
        ),
        86 => array(
            'CParVolgaStallTaljatti',
            'CParMetallobaza',
            'CParStenly',
            'CParMetalLadaTollatti',
        ),
        87 => array(
            'CParSPKKirov',
            'CParStalProKirov',
        ),
        88 => array(
            'CParTitan',
        ),
        89 => array(
            'CParAGMYakutsk',
        ),
        90 => array(
            'CParAgmYuzhnoSahalinsk'
        )
    );
    /*public $list_parsers = array(
        1 => array(
            'CParStroyOpt', //тройка
            'CParRealMetiz',//Стройка
            'CParStroyMaterialy',//Стройка
            'CParPiloTorg',//Стройка
            'CParMosKeram', //стройка
            'CParRosatom',//стройка
            'CParIsolux',//стройка
            'CParMosVodProm',//стройка
            'CParKJBK',//стройка
            'CParDorPlit',//стройка
            'CParTigiKnauf',//стройка
            'CParKirpichRu',//стройка
            'CParGlavStroyKomplekt',//стройка
            'CParTorgstroymat',//стройка
            'CParShopvira',//стройка
            'CParPPKEtalon',//стройка
            'CParStroyZhelezoBeton',//стройка
            'CParJbi4',//стройка
            'CParStroyZhelezoBeton',//стройка
            'CParJbi4',//стройка
            'CParJbi1',//стройка
            'CParOblCeram',//стройка
            'CParMerkuryStroy',//стройка
            'CParPasTermo',//стройка
            'CParCosmoplast',//стройка
            'CParPervoyaOcheredStroitelstva',//стройка
            'CParJBIDostavka',//стройка
            'CParTrestJBI',//стройка
            'CParPromJBIKomplect',//стройка
            'CParJBI4Zavod',//стройка
            'CParJBIKomplekt',//стройка
            'CParInjPromJelezoBeton',//стройка
            'CParMegaTorg',//стройка
            'CParOKZ',//стройка
            'CParBuildt',//новый
            'CParGbi11',//новый
            'CParKirpichMoskva',//новый
            'CParMirGbi',//новый
            'CParOlimpholding',//новый
            'CParRabotiagiStroy',//новый
            'CParSmv100',//новый
            'CParStroyHolding',//новый
            'CParDianStroy',//новый
            'CParParutonn',//новый
            'CParPromGbiKomplekt',//новый
            'CParRemontDostavka',//новый
            'CParSinergiaMsk',//новый
            'CParStroyShopper',//новый
            'CParVoloku',//новый
        ),
        2 => array(
            'CParJBIProm',//стройка
        ),
        53 => array(
            'CParAresSamorez', //стройка
        ),
    );*/
    public $emails = array(
        'misha@metal100.ru',
        'felix@metal100.ru',
        'petkich23@yandex.ru',
        'info@metal100.ru'
    );
}
