<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Company::factory()->count(1)->create();

        $companies = [
            [
                'city_id'     => 1,
                'package_id'  => 2,
                'name'        => ['en' => 'Reinger, Grimes and Schaden'],
                'email'       => 'padberg.sarina@example.com',
                'website'     => 'http://bruen.com/',
                'address'     => ['en' => '79830 Lang Hollow\nWest Tina, ID 76483-3572'],
                'specialties' => ['en' => 'Et ut aut tenetur eveniet. Similique rerum illum quo consectetur omnis quo est. Aliquid consectetur porro suscipit pariatur earum laudantium fugit. Deserunt molestiae eveniet modi praesentium omnis consequatur.\n\nEaque excepturi earum laborum omnis delectus quia architecto. Quasi officiis ea omnis alias veritatis necessitatibus consequuntur. Voluptatem aspernatur autem laudantium reprehenderit. Dolorem esse quas nam reprehenderit quae nisi temporibus.\n\nSit velit ut eius aut in. Alias doloremque et voluptates aliquam corporis. Tenetur inventore dolorum voluptatibus sequi. Voluptas sapiente voluptatem veritatis aliquid.'],
                'description' => ['en' => 'Et ex eos placeat eos accusantium. Fuga et veritatis id hic libero hic. Officia et magni et eaque quam a reprehenderit.\n\nDeleniti quidem unde sequi officiis molestias. Sint ea mollitia eveniet aperiam.\n\nId aut commodi ea dignissimos similique. Dolorem ut pariatur placeat laborum perferendis quas est. In dolorem et optio natus iure qui adipisci ex.'],
                'facebook'    => 'http://collier.com/occaecati-non-voluptatem-voluptatibus',
                'twitter'     => 'http://hansen.com/voluptatibus-et-aut-omnis-laboriosam-voluptatibus',
                'instagram'   => 'https://bauch.biz/doloribus-nobis-ut-asperiores-ex-velit.html',
                'linkedin'    => 'http://bayer.net/',
            ],
            [
                'package_id'  => 2,
                'name'        => ['en' => 'AHI Travel'],
                'email'       => 'info@ahitravel.com',
                'website'     => 'https://ahi.ahitravel.com/',
                'address'     => ['en' => 'International Tower, Suite 600 8550 W. Bryn Mawr Avenue Chicago, IL 60631'],
                'specialties' => ['en' => 'A look at our programs\nThe AHI team curates our experiences based on research they\'ve done themselves. In other words, they\'ve enjoyed every hotel, dined in every restaurant and gotten to know the locals before you\'ve even booked your trip. This familiarity with AHI locations allows our experts to bring the trip to life for you in new and exciting ways.\nProgram excursions including cultural tours and culinary experiences.\nENRICHMENT PROGRAMS\nAt AHI, we believe the world is your classroom. Our enrichment programs include lectures, talks and other educational experiences that dive a bit deeper into the history and culture of your location.\nAHI CONNECTS\nOur years of building relationships with locals have culminated in AHI Connects. These highly personalized experiences could include performances, special meals and other events that incorporate local culture and traditions.\nDISCOVERY EXCURSIONS\nThere are some sights you simply cannot miss. Our discovery excursions encompass everything wonderful about group travel as we guide you through all the must-see landmarks.\n'],
                'description' => ['en' => 'A note about our activity levels\nWe have rated all our excursions with activity levels to help you assess their physical demands. Look for the excursion ratings to make sure you can participate. For Further Adventure Travel, please see individual itineraries for activity details.\nMild | Walk under 1 mile on mainly flat terrain; navigate under 50 stairs; stand under 1 hour\n\nModerate | Walk 1-3 miles on mainly flat/uneven terrain; navigate 50-100 stairs; stand 1-2 hours\n\nActive | Walk 2+ miles on flat/uneven terrain including up and down inclines; navigate 100+ stairs; stand 3+ hours\n\nCatering to your preferences and needs\nWhile some of our travelers are always on the go, others prefer a bit more laid-back experience. That\'s why we offer you the chance to customize your vacation. Optional excursions and activities as well as plenty of free time give you total control over your trip.\n\nPersonalized adventures, electives and free time.\nPERSONALIZE YOUR JOURNEY\nWith our Personalize Your Journey program, AHI travelers can match a range of excursions to their interests and activity levels. From culinary tours to cycling adventures, there\'s bound to be an adventure perfect for you.'],
                'facebook'    => 'https://www.facebook.com/AHITRAVEL',
                'twitter'     => 'https://twitter.com/AHITRAVEL',
                'instagram'   => 'https://www.instagram.com/ahitravel/',
                'linkedin'    => 'https://www.linkedin.com/company/ahi-international/',
            ],
            [
                'package_id'  => 3,
                'name'        => ['en' => 'Test Company'],
                'email'       => 'test@company.com',
            ],
            [
                'city_id'     => 1,
                'package_id'  => 3,
                'name'        => ['en' => 'Hollywood Vacations'],
                'email'       => 'info@hollywood.com',
                'website'     => 'www.hollywoodv.com'
            ],
            [
                'city_id'     => 10,
                'package_id'  => 1,
                'name'        => ['en' => 'Nubia Tours'],
                'email'       => 'info@nubiatours.org',
                'website'     => 'http://nubiatours.org/',
                'address'     => ['en' => '56 Al-Madinah Al-Monawara St, Mohandeseen'],
                'specialties' => ['en' => 'We have a wide range of vehicles at your service that meet the international standards of safety (Jeeps, buses, limousines, luxury coaches..etc) They are all operated by experienced, multi lingual , qualified drivers to ensure that your trips are comfortable and safe.\nWe customize itineraries according to your budget, travel duration and needs. Whether you are travelling as a family, a couple or solo, for a month, week or even a few days, we will guarantee that you receive an amazing experience.\nWe have a skilled team dedicated to support you throughout your stay who are on call 24 hours a day, 7 days a week.\nOur itineraries have the most competitive prices and are customized in the shortest time possible, courtesy of our state of the art technology and highly trained staff.\nWe provide a warm and welcoming greeting upon your arrival by our team of guides who are extremely helpful. They will be providing you with welcome packs that contain information and vouchers that will make your stay rewarding, comfortable and enjoyable.'],
                'description' => ['en' => 'Nubia Tours was founded in 2007 in Egypt, with an aim to serve sophisticated travelers to Egypt, Morocco, Jordan and North of Sudan.\nHaytham Atwan, the CO-Founder & Vice President used to work for Abercrombie & Kent - Egypt for 11 years before establishing his own venture. His last role there was managing the AK\'s brand Offices in USA /UK & Australia as well handling the entire Virtuoso, NG groups and celebrities\' visits.\nOur expertise has given us the opportunity to form reliable partnerships with high-end agents in the USA, and we are proud to have organized a great number of exhibitions and programs for prestigious institutions such as the Art Arkansas Museum, the National Museum of Women Art in Atlanta and the Cincinnati Museum.'],
                'facebook'    => 'https://www.facebook.com/profile.php?id=100063872115325',
                'twitter'     => 'https://twitter.com/Nubia_tours',
            ],
            [
                'city_id'     => 1,
                'package_id'  => 2,
                'name'        => ['en' => 'High End Journeys'],
                'email'       => 'info@HighEndJourneys.com',
                'website'     => 'highendjourneys.com',
                'address'     => ['en' => '2 Ayesha El Taimoraya, Garden City'],
                'specialties' => ['en' => 'At High End Journeys we go out of our way to make your journey our journey, taking you through different worlds, times and cultures, each more exciting and unforgettable as the one before.\n\nCamel rides by the Pyramids & cruises alongside ancient temples? We’ve got those. Sunrise at the Sphinx & sunset by the Citadel? That’s one of our classic days out in Cairo. Desert excursions & stargazing in the mountains? Get ready for an unforgettable journey.  \n\nWhether you’re a luxury-oriented tourist or a hardened explorer, we have the professional experience, local contacts, and passion to make this an unforgettable and transformative journey. Creating custom, luxury tours that take you above and beyond a prepackaged experience is what we have been doing for our travelers since 2003. It is no wonder we only have five-star ratings on TripAdvisor.'],
                'description' => ['en' => 'High End Journeys is comprised of a team of trusted and passionate travel specialists. Founded by two highly experienced Egyptologists, High End Journeys is focused entirely on VIP clients who are looking for tailor-made luxury journeys around Egypt. Our journeys are completely based around your interests and schedule, they are one-of-a-kind experiences that are as unique as our clientele. Our expertise, knowledge, and personal relationships allow us to design journeys that are rugged and adventurous, urban and sophisticated, or the perfect blend of both. Whichever you prefer, you can be certain that your trip will be filled with exclusive opportunities that only we only can offer.'],
                'facebook'    => 'https://www.facebook.com/highendjourneys',
                'instagram'   => 'https://www.instagram.com/highendjourneys/',
                'linkedin'    => 'https://www.linkedin.com/company/highendjourneys',
            ],
            [
                'city_id'     => 10,
                'package_id'  => 2,
                'name'        => ['en' => 'egitalloyd'],
                'email'       => 'travel@egitalloyd.com',
                'website'     => 'http://www.egitalloyd.com/',
                'address'     => ['en' => '20 El Nahda St., Dokki '],
                'specialties' => ['en' => 'Egitalloyd has the feel of a small company that places a premium on high-touch customer service delivered by an exceptionally qualified staff which exceeds industry averages for years of experience and agency tenure.This mentality, combined with a hands-on approach to customer service, has earned us a fiercely loyal following of clients who attest to our superior service capabilities Staff members are passionate advocates for service to clients, while at the same time seeking creative ways to make connections that contribute significantly to a positive quality and a sound business environment for all.\n\nOn behalf of Egitalloyd Travel, we would like to welcome you all to visit our country Egypt, land of the Pharaohs, we would like to welcome you as a colleague in the tourism field to explore the beauty of our country or as a business partner to inspect, see what you will sell to your travelers and feel the hospitality of the Egyptians.'],
                'description' => ['en' => 'Egitalloyd Travel Egypt was established in 1954 with a license number 42, now it’s a new management by Mr. Mamdouh el-Sebai, owner & president of Egitalloyd Travel since 1998, who was in the travel business for 35 years. In 2008, he is the President of the American Society of Travel Agents (ASTA) – Egypt Chapter.\n\n Egitalloyd is able to provide a wide range of travel services such as meet and assist at the airport by our professional representatives, hotels accommodation, Nile cruises and Lake Nasser cruises booking, private touring with best Egyptologists, private transportation by vans and buses model of the year, domestic and international airline tickets reservations system and charter flights within Egypt. Egitalloyd provides different trips for exploring Egypt, such as Archeology, Islamic Heritage, Egyptian Christian Coptic churches and monasteries, Scuba diving, Golf, Safari, Honeymoon, Oases trips along the Western Desert of Egypt. All our package tours are for groups and individuals. Tailor made itineraries by our professional specialists to suit all types of travelers.'],
                'facebook'    => 'https://www.facebook.com/EgitalloydTravel',
                'twitter'     => 'https://twitter.com/EgitalloydEgypt',
                'instagram'   => 'https://www.instagram.com/egitalloyd_travel/',
            ],
            [
                'package_id' => 2,
                'name'       => ['en' => 'hollywood vacation'],
                'email'      => 'ahmed@hollywood.com',
            ]
        ];

        foreach ($companies as $company) {
            Company::create($company);
        }
    }
}
