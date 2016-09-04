<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Comment;
use AppBundle\Entity\NoticeType,
    AppBundle\Entity\Role,
    AppBundle\Entity\appConfig,
    AppBundle\Entity\Business,
    AppBundle\Entity\Schedule,
    AppBundle\Entity\Category,
    AppBundle\Entity\Product,
    AppBundle\Entity\File,
    AppBundle\Entity\User,
    AppBundle\Entity\Animation;
use AppBundle\Utils\Utils;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;

class loadDefaultDB extends AbstractFixture
{

    public function load(ObjectManager $manager)
    {
        // Notice Type
        $notices = [
            'noticeComment' => ['Comentario de cliente', 'comment'],
            'noticeEmail' => ['Mensaje de cliente', 'email'],
            'noticeOffer' => ['Terminación de oferta', 'offer']
        ];

        foreach ($notices as $name => $notice) {
            $tmpNotice = new NoticeType();
            $tmpNotice
                ->setName($notice[0])
                ->setType($notice[1]);
            $this->addReference($name, $tmpNotice);
            $manager->persist($tmpNotice);
        }

        // Role Info
        $arrRoles = [
            'roleModerator' => ['Moderador', 'ROLE_MODERATOR'],
            'roleAdmin' => ['Administrador', 'ROLE_ADMIN']
        ];

        // Add Data Role
        foreach ($arrRoles as $role => $info) {
            $tmpRole = new Role();
            $tmpRole
                ->setName($info[0])
                ->setRole($info[1]);
            $this->addReference($role, $tmpRole);
            $manager->persist($tmpRole);
        }

        // Add Admin Photo
        $adminPhoto = new File();
        $adminPhoto
            ->setName('admin.jpg');
        $this->addReference('adminPhoto', $adminPhoto);
        $manager->persist($adminPhoto);

        // Add Admin User
        $adminUser = new User();
        $adminUser
            ->setName('Admin')
            ->setLastname('of System')
            ->setPhoto($this->getReference('adminPhoto'))
            ->setUser('admin')
            ->setPassword('Admin.2015')
            ->addRole($this->getReference('roleAdmin'));

        // Add Moderator User
        $mUser = new User();
        $mUser
            ->setName('larry')
            ->setLastname('ruiz fundora')
            ->setUser('larry')
            ->setPassword('Soloyo2015')
            ->addRole($this->getReference('roleModerator'));

        // Entripting
        $encrip = new Utils();
        $encrip->setUserPassword($adminUser);
        $encrip->setUserPassword($mUser);

        $manager->persist($adminUser);
        $manager->persist($mUser);

        // Add Data Configuration
        // Add logos
        $arrAppImages = [
            ['appLogoTop', 'logo-top.png'],
            ['appLogoDown', 'logo-down.png'],
            ['appParallax', 'parallax.jpg'],
            ['appOffer', 'offer.jpg']
        ];

        foreach ($arrAppImages as $img) {
            $tmpAppFile = new File();
            $tmpAppFile
                ->setName($img[1])
                ->setUpdated(new \DateTime('now'));
            $this->addReference($img[0], $tmpAppFile);
            $manager->persist($tmpAppFile);
        }

        // Add Info Images
        $arrAppInfoImages = [
            ['appInfoImage1', 'image01.jpg'],
            ['appInfoImage2', 'image02.jpg'],
            ['appInfoImage3', 'image03.jpg'],
            ['appInfoImage4', 'image04.jpg']
        ];

        $arrAppCarousel = [
            ['appCarousel01', 'image01.jpg'],
            ['appCarousel02', 'image02.jpg'],
            ['appCarousel03', 'image03.jpg'],
            ['appCarousel04', 'image04.jpg'],
            ['appCarousel05', 'image05.jpg'],
            ['appCarousel06', 'image06.jpg'],
            ['appCarousel07', 'image07.jpg'],
            ['appCarousel08', 'image08.jpg'],
            ['appCarousel09', 'image09.jpg'],
            ['appCarousel10', 'image10.jpg']
        ];

        // Add App Info
        $appConfig = new appConfig();
        $appConfig
            ->setSystem('system')
            ->setName('GINA')
            ->setSlogan('ATENDERLO SERÁ UN PLACER!')
            ->setPublicity('Todo nuestro esfuerzo, profesionalidad, cariño y calidad del servicio en funsión de su satisfacción, placer y disfrute de cada momento que pase con nosotros. Haremos que su vida cambie porque usted se lo merece.')
            ->setInfoTitle('Título')
            ->setInfoText('Cuerpo del documento...')
            ->setAddress('1ra Ave, #512 % Calle 5 y 6, Santa Marta, Cárdenas, Matanzas, Cuba.')
            ->setPhone('+53 45 619582')
            ->setOwner('Luis León Pérez, Raúl González Cruz, Miguel Pardo Fuentes')
            ->setLogoTop($this->getReference('appLogoTop'))
            ->setLogoDown($this->getReference('appLogoDown'))
            ->setImgParallax($this->getReference('appParallax'))
            ->setImgOffer($this->getReference('appOffer'))
            ->setSloganOffer('Más de lo que espera, por menos de lo que imaginas.')
            ->setShowCarousel(false)
            ->setEmail('support@gina.esy.es');

        foreach ($arrAppInfoImages as $img) {
            $tmpAppFile = new File();
            $tmpAppFile
                ->setName($img[1])
                ->setUpdated(new \DateTime('now'));
            $this->addReference($img[0], $tmpAppFile);
            $manager->persist($tmpAppFile);
            $appConfig->addInfoImage($manager->merge($this->getReference($img[0])));
        }

        foreach ($arrAppCarousel as $img) {
            $tmpAppFile = new File();
            $tmpAppFile
                ->setName($img[1])
                ->setUpdated(new \DateTime('now'));
            $this->addReference($img[0], $tmpAppFile);
            $manager->persist($tmpAppFile);
            $appConfig->addCarouselImage($manager->merge($this->getReference($img[0])));
        }

        $this->addReference('appConfig', $appConfig);
        $manager->persist($appConfig);

        // Business Info
        $arrBusiness = [
            'gina-fashion' => [
                'GINA Fashion',
                '1ra Ave, #512 % Calle 5 y 6, Santa Marta, Cárdenas, Matanzas, Cuba.',
                '619582',
                'ginafashion@gina.esy.es',
                'imgThumb' => [
                    'gina-fashion-thumb.jpg'
                ],
                'imgPublicity' => [
                    'gina-fashion-public.jpg'
                ],
                'imgPublicityAnimation' => 'a02',
                'Su estilo, le dice quién es!',
                'es un salón de belleza profesional y cuenta con todo el equipamiento y el personal calificado para satisfacer al más exigente y exclusivo cliente.',
                'schedule' => [
                    [false, '8:00 AM', '10:00 PM', false, 'Lunes', 'Sábado'],
                    [false, '8:00 AM', '5:00 PM', false, 'Domingo', null]
                ],
                'categories' => [
                    'Peinados' => [
                        [
                            'Cebolla',
                            'Descripción de la cebolla',
                            '1.99',
                            'offer' => [
                                1,
                                '2015-08-30',
                                '2015-10-25',
                                '0.30',
                                'Sin condición'
                            ],
                            'photo' => [
                                'gina-fashion-product.jpg'
                            ],
                            'comments' => [
                                ['Ana', 'ana@nauta.cu', 'WAO!!!, que lindo, me ha encantado...'],
                                ['Larry', 'larry@nauta.cu', 'jajajaja, no se que le ves de novedoso, pero a la verdad, que se ve linda.'],
                                ['Yohn', 'yohn@gmail.com', 'jajaja, yo prefiero comermela, claro está, la cebolla... ;)']
                            ]
                        ],
                        [
                            'Torniquete',
                            'Descripción del torniquete',
                            '2.99',
                            'offer' => [0],
                            'photo' => [
                                'gina-fashion-product.jpg'
                            ]
                        ],
                        [
                            'Derriz',
                            'Descripción del torniquete',
                            '2.99',
                            'offer' => [
                                1,
                                '2015-08-30',
                                '2015-10-25',
                                '0.35',
                                'Sin condición'
                            ],
                            'photo' => [
                                'gina-fashion-product.jpg'
                            ]
                        ],
                        [
                            'Laciado',
                            'Descripción del torniquete',
                            '2.99',
                            'offer' => [
                                1,
                                '2015-08-30',
                                '2015-09-25',
                                '0.10',
                                'Sin condición'
                            ],
                            'photo' => [
                                'gina-fashion-product.jpg'
                            ],
                            'comments' => [
                                ['Jenny', 'jenny@nauta.cu', 'WAO!!!, que lindo, me ha encantado...'],
                                ['Peter', 'pe@nauta.cu', 'jajajaja, a la verdad, que se ve linda. Cómo te verás tú???'],
                                ['Lester', 'yohn@gmail.com', 'jajaja, men tienes el plomo activado... 3:)']
                            ]
                        ]
                    ],
                    'Tintes' => [
                        [
                            'Negro',
                            'Descripción del tinte negro',
                            '1.49',
                            'offer' => [0],
                            'photo' => [
                                'gina-fashion-product.jpg'
                            ]
                        ],
                        [
                            'Rojo',
                            'Descripción del tinte negro',
                            '1.49',
                            'offer' => [0],
                            'photo' => [
                                'gina-fashion-product.jpg'
                            ]
                        ],
                        [
                            'Rubio',
                            'Descripción del tinte negro',
                            '1.49',
                            'offer' => [0],
                            'photo' => [
                                'gina-fashion-product.jpg'
                            ]
                        ],
                        [
                            'Iluminaciones',
                            'Descripción del tinte negro',
                            '1.49',
                            'offer' => [
                                1,
                                '2015-08-30',
                                '2015-10-25',
                                '0.05',
                                'Sin condición'
                            ],
                            'photo' => [
                                'gina-fashion-product.jpg'
                            ]
                        ]
                    ],
                    'Cortes de Cabello' => [
                        [
                            'Degrafilado',
                            'Descripción del tinte negro',
                            '1.49',
                            'offer' => [0],
                            'photo' => [
                                'gina-fashion-product.jpg'
                            ]
                        ],
                        [
                            'Cuadrado',
                            'Descripción del tinte negro',
                            '1.49',
                            'offer' => [0],
                            'photo' => [
                                'gina-fashion-product.jpg'
                            ]
                        ],
                        [
                            'Yonky',
                            'Descripción del tinte negro',
                            '1.49',
                            'offer' => [0],
                            'photo' => [
                                'gina-fashion-product.jpg'
                            ]
                        ],
                        [
                            'El Tiburón',
                            'Descripción del tinte negro',
                            '1.49',
                            'offer' => [0],
                            'photo' => [
                                'gina-fashion-product.jpg'
                            ]
                        ]
                    ]
                ]
            ],
            'gina-boutique' => [
                'GINA Boutique',
                '1ra Ave, #512 % Calle 5 y 6, Santa Marta, Cárdenas, Matanzas, Cuba.',
                '619582',
                'ginaboutique@gina.esy.es',
                'imgThumb' => [
                    'gina-boutique-thumb.jpg'
                ],
                'imgPublicity' => [
                    'gina-boutique-public.jpg'
                ],
                'imgPublicityAnimation' => 'a01',
                'Su imagen, es lo más importante!',
                'es una exclusiva tienda de productos testiles, calzado y bisutería. Diseñada y equipada para complacer a cualquier cliente.',
                'schedule' => [
                    [false, '8:00 AM', '5:00 PM', false, 'Lunes', 'Viernes'],
                    [false, '8:00 AM', '8:00 PM', false, 'Sábado', 'Domingo']
                ],
                'categories' => [
                    'Cartera de Mujer' => [
                        [
                            'Cartera Pequeña',
                            'Descripción de la Cartera Pequeña',
                            '1.99',
                            'offer' => [
                                1,
                                '2015-08-30',
                                '2015-09-15',
                                '0.08',
                                'Sin condición'
                            ],
                            'photo' => [
                                'gina-boutique-product.jpg'
                            ],
                            'comments' => [
                                ['Jenny', 'jenny@nauta.cu', 'WAO!!!, que lindo, me ha encantado...'],
                                ['Peter', 'pe@nauta.cu', 'qué caro!!!!!!!!!!!!!'],
                                ['Lester', 'yohn@gmail.com', 'jajaja, tacaño...'],
                                ['Peter', 'pe@nauta.cu', 'y eso a tí que te importa'],
                                ['Lester', 'yohn@gmail.com', 'jajaja, y para colmo grosero!!! :(']
                            ]
                        ],
                        [
                            'Cartera Mediana',
                            'Descripción de la Cartera Mediana',
                            '1.99',
                            'offer' => [0],
                            'photo' => [
                                'gina-boutique-product.jpg'
                            ]
                        ],
                        [
                            'Cartera Grande',
                            'Descripción de la Cartera Grande',
                            '1.99',
                            'offer' => [0],
                            'photo' => [
                                'gina-boutique-product.jpg'
                            ]
                        ]
                    ],
                    'Cartera de Hombre' => [
                        [
                            'Cartera Pequeña',
                            'Descripción de la Cartera Pequeña',
                            '1.49',
                            'offer' => [0],
                            'photo' => [
                                'gina-boutique-product.jpg'
                            ]
                        ],
                        [
                            'Cartera de Cuero',
                            'Descripción de la Cartera Pequeña',
                            '1.49',
                            'offer' => [0],
                            'photo' => [
                                'gina-boutique-product.jpg'
                            ]
                        ],
                        [
                            'Cartera de Lona',
                            'Descripción de la Cartera Pequeña',
                            '1.49',
                            'offer' => [0],
                            'photo' => [
                                'gina-boutique-product.jpg'
                            ]
                        ]
                    ],
                    'Pantalon de Hombre' => [
                        [
                            'Elástizado',
                            'Descripción de la Cartera Pequeña',
                            '1.49',
                            'offer' => [0],
                            'photo' => [
                                'gina-boutique-product.jpg'
                            ]
                        ],
                        [
                            'Tela',
                            'Descripción de la Cartera Pequeña',
                            '1.49',
                            'offer' => [
                                1,
                                '2015-08-30',
                                '2015-09-15',
                                '0.25',
                                'Sin condición'
                            ],
                            'photo' => [
                                'gina-boutique-product.jpg'
                            ]
                        ],
                        [
                            'Hilo',
                            'Descripción de la Cartera Pequeña',
                            '1.49',
                            'offer' => [0],
                            'photo' => [
                                'gina-boutique-product.jpg'
                            ]
                        ],
                        [
                            'Mesclilla',
                            'Descripción de la Cartera Pequeña',
                            '1.49',
                            'offer' => [
                                1,
                                '2015-08-30',
                                '2015-09-15',
                                '0.11',
                                'Sin condición'
                            ],
                            'photo' => [
                                'gina-boutique-product.jpg'
                            ]
                        ]
                    ],
                    'Pantalon de Mujer' => [
                        [
                            'Elástizado',
                            'Descripción de la Cartera Pequeña',
                            '1.49',
                            'offer' => [0],
                            'photo' => [
                                'gina-boutique-product.jpg'
                            ]
                        ],
                        [
                            'Tela',
                            'Descripción de la Cartera Pequeña',
                            '1.49',
                            'offer' => [
                                1,
                                '2015-08-30',
                                '2015-09-15',
                                '0.25',
                                'Sin condición'
                            ],
                            'photo' => [
                                'gina-boutique-product.jpg'
                            ]
                        ],
                        [
                            'Hilo',
                            'Descripción de la Cartera Pequeña',
                            '1.49',
                            'offer' => [0],
                            'photo' => [
                                'gina-boutique-product.jpg'
                            ]
                        ],
                        [
                            'Mesclilla',
                            'Descripción de la Cartera Pequeña',
                            '1.49',
                            'offer' => [
                                1,
                                '2015-08-30',
                                '2015-09-15',
                                '0.11',
                                'Sin condición'
                            ],
                            'photo' => [
                                'gina-boutique-product.jpg'
                            ]
                        ],
                        [
                            'Lícra',
                            'Descripción de la Cartera Pequeña',
                            '1.49',
                            'offer' => [
                                1,
                                '2015-08-30',
                                '2015-09-15',
                                '0.11',
                                'Sin condición'
                            ],
                            'photo' => [
                                'gina-boutique-product.jpg'
                            ]
                        ]
                    ]
                ]
            ],
            'gina-bar' => [
                'GINA Restaurant',
                '1ra Ave, #512 % Calle 5 y 6, Santa Marta, Cárdenas, Matanzas, Cuba.',
                '619582',
                'ginarestaurant@gina.esy.es',
                'imgThumb' => [
                    'gina-bar-thumb.jpg'
                ],
                'imgPublicity' => [
                    'gina-bar-public.jpg'
                ],
                'imgPublicityAnimation' => 'a03',
                'Su paladar, el más exquisito!',
                'es un Bar con todo tipo de bebida, los más exquisitos tragos y exóticos sabores, además de contar con un pequeño Restaurante especializado en comida rápida, con un excelente servicio para los más exigentes paladares.',
                'schedule' => [
                    [false, '8:00 AM', '12:00 AM', false, 'Lunes', 'Viernes'],
                    [true, null, null, false, 'Sábado', 'Domingo']
                ],
                'categories' => [
                    'Jugos' => [
                        [
                            'Jugo de Naranja',
                            'Descripción del Jugo de Naranja',
                            '1.99',
                            'offer' => [
                                1,
                                '2015-08-30',
                                '2015-10-30',
                                '0.40',
                                'Sin condición'
                            ],
                            'photo' => [
                                'gina-bar-product.jpg'
                            ],
                            'comments' => [
                                ['Jenny', 'jenny@nauta.cu', 'WAO!!!, que rico, me ha encantado...'],
                                ['Peter', 'pe@nauta.cu', 'qué caro!!!!!!!!!!!!!'],
                                ['Lester', 'yohn@gmail.com', 'jajaja, tacaño...'],
                                ['Peter', 'pe@nauta.cu', 'y eso a tí que te importa'],
                                ['Lester', 'yohn@gmail.com', 'jajaja, y para colmo grosero!!! :(']
                            ]
                        ],
                        [
                            'Jugo de Melón',
                            'Descripción del Jugo de Melón',
                            '1.99',
                            'offer' => [0],
                            'photo' => [
                                'gina-bar-product.jpg'
                            ]
                        ],
                        [
                            'Jugo de Guayaba',
                            'Descripción del Jugo de Guayaba',
                            '1.99',
                            'offer' => [
                                1,
                                '2015-08-30',
                                '2015-11-05',
                                '0.23',
                                'Sin condición'
                            ],
                            'photo' => [
                                'gina-bar-product.jpg'
                            ]
                        ],
                        [
                            'Jugo de Maracuyá',
                            'Descripción del Jugo de Melón',
                            '1.99',
                            'offer' => [0],
                            'photo' => [
                                'gina-bar-product.jpg'
                            ]
                        ],
                        [
                            'Jugo de Pera',
                            'Descripción del Jugo de Melón',
                            '1.99',
                            'offer' => [0],
                            'photo' => [
                                'gina-bar-product.jpg'
                            ],
                            'comments' => [
                                ['Jenny', 'jenny@nauta.cu', 'WAO!!!, que lindo, me ha encantado...'],
                                ['Luis', 'luis@nauta.cu', 'si, creete que es pera de verdad... sabe dios que será porque aquí, que yo sepa no se cultiva pera']
                            ]
                        ],
                        [
                            'Jugo de Manzana',
                            'Descripción del Jugo de Melón',
                            '1.99',
                            'offer' => [0],
                            'photo' => [
                                'gina-bar-product.jpg'
                            ]
                        ]
                    ],
                    'Bebidas de Brindis' => [
                        [
                            'Sidra',
                            'Descripción del Mojito',
                            '1.00',
                            'offer' => [0],
                            'photo' => [
                                'gina-bar-product.jpg'
                            ]
                        ],
                        [
                            'Champán',
                            'Descripción del Mojito',
                            '1.00',
                            'offer' => [
                                1,
                                '2015-08-30',
                                '2015-11-05',
                                '0.02',
                                'Sin condición'
                            ],
                            'photo' => [
                                'gina-bar-product.jpg'
                            ],
                            'comments' => [
                                ['Evelyn', 'evelyn@nauta.cu', 'Adoro el Champán... y hacia tiempo que no me tomaba uno igual... está super rico!!!'],
                                ['Peter', 'pe@nauta.cu', 'qué caro!!!!!!!!!!!!!'],
                                ['Evelyn', 'evelyn@nauta.cu', 'Papi, yo no tengo ni gatico ni perrito, así que puedo darme todos los lujos que quiera, allá tú... jajajaja'],
                            ]
                        ]
                    ],
                    'Tragos sin alcohol' => [
                        [
                            'Mojito',
                            'Descripción del Mojito',
                            '1.00',
                            'offer' => [0],
                            'photo' => [
                                'gina-bar-product.jpg'
                            ]
                        ],
                        [
                            'Piña colada',
                            'Descripción del Mojito',
                            '1.00',
                            'offer' => [
                                1,
                                '2015-08-30',
                                '2015-11-05',
                                '0.02',
                                'Sin condición'
                            ],
                            'photo' => [
                                'gina-bar-product.jpg'
                            ]
                        ]
                    ],
                    'Licores' => [
                        [
                            'Licor de Piña',
                            'Descripción del Mojito',
                            '1.00',
                            'offer' => [0],
                            'photo' => [
                                'gina-bar-product.jpg'
                            ]
                        ],
                        [
                            'Licor de Café',
                            'Descripción del Mojito',
                            '1.00',
                            'offer' => [
                                1,
                                '2015-08-30',
                                '2015-11-05',
                                '0.02',
                                'Sin condición'
                            ],
                            'photo' => [
                                'gina-bar-product.jpg'
                            ]
                        ]
                    ],
                    'Tragos con alcohol' => [
                        [
                            'Ron Habana Club',
                            'Descripción del Mojito',
                            '1.00',
                            'offer' => [0],
                            'photo' => [
                                'gina-bar-product.jpg'
                            ]
                        ],
                        [
                            'Wisky',
                            'Descripción del Mojito',
                            '1.00',
                            'offer' => [0],
                            'photo' => [
                                'gina-bar-product.jpg'
                            ]
                        ],
                        [
                            'Brandy',
                            'Descripción del Mojito',
                            '1.00',
                            'offer' => [0],
                            'photo' => [
                                'gina-bar-product.jpg'
                            ]
                        ],
                        [
                            'Tequila',
                            'Descripción del Mojito',
                            '1.00',
                            'offer' => [
                                1,
                                '2015-08-30',
                                '2015-11-05',
                                '0.13',
                                'Sin condición'
                            ],
                            'photo' => [
                                'gina-bar-product.jpg'
                            ]
                        ],
                        [
                            'Volka',
                            'Descripción del Mojito',
                            '1.00',
                            'offer' => [
                                1,
                                '2015-08-30',
                                '2015-11-05',
                                '0.13',
                                'Sin condición'
                            ],
                            'photo' => [
                                'gina-bar-product.jpg'
                            ]
                        ]
                    ],
                    'Vinos' => [
                        [
                            'Vino Blanco',
                            'Descripción del Mojito',
                            '1.00',
                            'offer' => [0],
                            'photo' => [
                                'gina-bar-product.jpg'
                            ]
                        ],
                        [
                            'Vino Rojo',
                            'Descripción del Mojito',
                            '1.00',
                            'offer' => [0],
                            'photo' => [
                                'gina-bar-product.jpg'
                            ]
                        ],
                        [
                            'Vino Tinto',
                            'Descripción del Mojito',
                            '1.00',
                            'offer' => [0],
                            'photo' => [
                                'gina-bar-product.jpg'
                            ]
                        ],
                        [
                            'Vino Rosado',
                            'Descripción del Mojito',
                            '1.00',
                            'offer' => [
                                1,
                                '2015-08-30',
                                '2015-11-05',
                                '0.13',
                                'Sin condición'
                            ],
                            'photo' => [
                                'gina-bar-product.jpg'
                            ]
                        ],
                        [
                            'Vino Dulce',
                            'Descripción del Mojito',
                            '1.00',
                            'offer' => [
                                1,
                                '2015-08-30',
                                '2015-11-05',
                                '0.03',
                                'Sin condición'
                            ],
                            'photo' => [
                                'gina-bar-product.jpg'
                            ]
                        ],
                        [
                            'Vino Amargo',
                            'Descripción del Mojito',
                            '1.00',
                            'offer' => [
                                1,
                                '2015-08-30',
                                '2015-11-05',
                                '0.13',
                                'Sin condición'
                            ],
                            'photo' => [
                                'gina-bar-product.jpg'
                            ]
                        ]
                    ]
                ]
            ]
        ];

        // Add Animations
        $arrAnimation = [
            ['a01', 'Aparecer', 'fade'],
            ['a02', 'Corte Vertical', '3dcurtain-vertical'],
            ['a03', 'Cubo Horizontal', 'incube-horizontal'],
            ['a04', 'Cuadrados', 'flash']
        ];

        foreach ($arrAnimation as $animate) {
            $bPublicAnimate = new Animation();
            $bPublicAnimate
                ->setName($animate[1])
                ->setType($animate[2]);
            $this->addReference($animate[0], $bPublicAnimate);
            $manager->persist($bPublicAnimate);
        }

        // Add Data Business
        $id = 0;
        $img = 0;
        foreach ($arrBusiness as $b => $business) {
            $bThumb = new File();
            $bThumb
                ->setName($business['imgThumb'][0])
                ->setUpdated(new \DateTime('now'));
            $this->addReference($b . 'imgThumb', $bThumb);
            $manager->persist($bThumb);

            $bPublic = new File();
            $bPublic
                ->setName($business['imgPublicity'][0])
                ->setUpdated(new \DateTime('now'));
            $this->addReference($b . 'imgPublicity', $bPublic);
            $manager->persist($bPublic);

//            $bPublicAnimate = new Animation();
//            $bPublicAnimate
//                ->setName($business['imgPublicityAnimation'][0])
//                ->setType($business['imgPublicityAnimation'][1]);
//            $this->addReference($b . 'imgPublicityAnimation', $bPublicAnimate);
//            $manager->persist($bPublicAnimate);

            $tmpBusiness = new Business();
            $tmpBusiness
                ->setName($business[0])
                ->setAddress($business[1])
                ->setPhone($business[2])
                ->setEmail($business[3])
                ->setImgThumb($this->getReference($b . 'imgThumb'))
                ->setImgPublicity($this->getReference($b . 'imgPublicity'))
                ->setImgPublicityAnimation($this->getReference($business['imgPublicityAnimation']))
                ->setSlogan($business[4])
                ->setDescription($business[5])
                ->setConfig($this->getReference('appConfig'));
            $this->addReference($b, $tmpBusiness);
            $manager->persist($tmpBusiness);

            foreach ($business['schedule'] as $schedule) {
                $tmpSchedule = new Schedule();
                $tmpSchedule
                    ->setIsAllHours($schedule[0])
                    ->setTimeStart(date_create_from_format('Y-m-d',$schedule[1]))
                    ->setTimeEnd(date_create_from_format('Y-m-d',$schedule[2]))
                    ->setIsAllDays($schedule[3])
                    ->setDayStart($schedule[4])
                    ->setDayEnd($schedule[5])
                    ->setBusiness($this->getReference($b));
                $manager->persist($tmpSchedule);
                $tmpBusiness->addSchedule($tmpSchedule);
                $manager->persist($tmpBusiness);
            }

            foreach ($business['categories'] as $category => $products) {

                $tmpCategory = new Category();
                $tmpCategory
                    ->setName($category)
                    ->setBusiness($this->getReference($b));
                $this->addReference('category' . $id, $tmpCategory);
                $manager->persist($tmpCategory);

                foreach ($products as $product) {

                    $pPhoto = new File();
                    $pPhoto
                        ->setName($product['photo'][0])
                        ->setUpdated(new \DateTime('now'));
                    $this->addReference('productPhoto' . $img, $pPhoto);
                    $manager->persist($pPhoto);

                    $isOffer = $product['offer'][0];
                    $tmpProduct = new Product();
                    $tmpProduct
                        ->setName($product[0])
                        ->setDescription($product[1])
                        ->setPrice($product[2])
                        ->setIsOffer($isOffer)
                        ->addPhoto($this->getReference('productPhoto' . $img))
                        ->setCategory($this->getReference('category' . $id));
                    $this->addReference('product' . $img, $tmpProduct);

                    if ($isOffer) {
                        $tmpProduct
                            ->setPublicated(date_create_from_format('Y-m-d',$product['offer'][1]))
                            ->setExpired(date_create_from_format('Y-m-d',$product['offer'][2]))
                            ->setDiscount($product['offer'][3])
                            ->setConditions($product['offer'][4]);
                    }

                    if(isset($product['comments']))
                    {
                        foreach($product['comments'] as $comment)
                        {
                            $tmpComment = new Comment();
                            $tmpComment
                                ->setName($comment[0])
                                ->setEmail($comment[1])
                                ->setText($comment[2])
                                ->setProduct($this->getReference('product' . $img));
                            $manager->persist($tmpComment);
                        }
                    }

                    $manager->persist($tmpProduct);

                    $img++;
                }

                $id++;
            }

        }

        // Save All
        $manager->flush();

    }

    public function  getOrder()
    {
        return 1;
    }

} 