<?php

namespace App\Enums;

enum CategoryEnum: string
{
    case AdultFiction = 'adult-fiction';
    case ArtMusicPhotography = 'art-music-photography';
    case BiographiesMemoirs = 'biographies-memoirs';
    case BuddhismHinduism = 'buddhism-hinduism';
    case BusinessInvesting = 'business-investing';
    case ChildrenAge03 = 'children-age-0-3';
    case ChildrenAge47 = 'children-age-4-7';
    case ChildrenAge812 = 'children-age-8-12';
    case Christianity = 'christianity';
    case Classics = 'classics';
    case ComicsGraphicNovels = 'comics-graphic-novels';
    case ComputersTechnology = 'computers-technology';
    case EducationTestPreparation = 'education-test-preparation';
    case FictionLiterature = 'fiction-literature';
    case FoodDrinkCookbook = 'food-drink-cookbook';
    case HealthFitnessWellness = 'health-fitness-wellness';
    case HistoricalFiction = 'historical-fiction';
    case History = 'history';
    case HomeArchitecture = 'home-architecture';
    case HorrorParanormal = 'horror-paranormal';
    case HumorComedy = 'humor-comedy';
    case Islam = 'islam';
    case LawTax = 'law-tax';
    case MangaManhuaManhwa = 'manga-manhua-manhwa';
    case MotivationSelfHelp = 'motivation-self-help';
    case MysteryThrillerSuspense = 'mystery-thriller-suspense';
    case NonFiction = 'non-fiction';
    case ParentingRelationships = 'parenting-relationships';
    case Philosophy = 'philosophy';
    case PoemShortStory = 'poem-short-story';
    case PoliticsAffairsSocialSciences = 'politics-affairs-social-sciences';
    case ProfessionalEngineeringTechnical = 'professional-engineering-technical';
    case Psychology = 'psychology';
    case ReferenceDictionary = 'reference-dictionary';
    case ReligionSpirituality = 'religion-spirituality';
    case Romance = 'romance';
    case ScienceFictionFantasy = 'science-fiction-fantasy';
    case ScienceNature = 'science-nature';
    case SportsOutdoors = 'sports-outdoors';
    case TeenYoungAdultFiction = 'teen-young-adult-fiction';
    case Travel = 'travel';

    final public function name(): string
    {
        return match ($this) {
            self::AdultFiction => 'Adult Fiction',
            self::ArtMusicPhotography => 'Art, Music & Photography',
            self::BiographiesMemoirs => 'Biographies & Memoirs',
            self::BuddhismHinduism => 'Buddhism & Hinduism',
            self::BusinessInvesting => 'Business & Investing',
            self::ChildrenAge03 => 'Children Age 0-3',
            self::ChildrenAge47 => 'Children Age 4-7',
            self::ChildrenAge812 => 'Children Age 8-12',
            self::Christianity => 'Christianity',
            self::Classics => 'Classics',
            self::ComicsGraphicNovels => 'Comics & Graphic Novels',
            self::ComputersTechnology => 'Computers & Technology',
            self::EducationTestPreparation => 'Education & Test Preparation',
            self::FictionLiterature => 'Fiction & Literature',
            self::FoodDrinkCookbook => 'Food, Drink & Cookbook',
            self::HealthFitnessWellness => 'Health, Fitness & Wellness',
            self::HistoricalFiction => 'Historical Fiction',
            self::History => 'History',
            self::HomeArchitecture => 'Home & Architecture',
            self::HorrorParanormal => 'Horror & Paranormal',
            self::HumorComedy => 'Humor & Comedy',
            self::Islam => 'Islam',
            self::LawTax => 'Law & Tax',
            self::MangaManhuaManhwa => 'Manga, Manhua & Manhwa',
            self::MotivationSelfHelp => 'Motivation & Self-Help',
            self::MysteryThrillerSuspense => 'Mystery, Thriller & Suspense',
            self::NonFiction => 'Non-fiction',
            self::ParentingRelationships => 'Parenting & Relationships',
            self::Philosophy => 'Philosophy',
            self::PoemShortStory => 'Poem & Short Story',
            self::PoliticsAffairsSocialSciences => 'Politics, Affairs & Social Sciences',
            self::ProfessionalEngineeringTechnical => 'Professional, Engineering & Technical',
            self::Psychology => 'Psychology',
            self::ReferenceDictionary => 'Reference & Dictionary',
            self::ReligionSpirituality => 'Religion & Spirituality',
            self::Romance => 'Romance',
            self::ScienceFictionFantasy => 'Science Fiction & Fantasy',
            self::ScienceNature => 'Science & Nature',
            self::SportsOutdoors => 'Sports & Outdoors',
            self::TeenYoungAdultFiction => 'Teen & Young Adult Fiction',
            self::Travel => 'Travel',
        };
    }
}
