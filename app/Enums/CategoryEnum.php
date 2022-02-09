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
            self::AdultFiction,
            self::Christianity,
            self::Classics,
            self::HistoricalFiction,
            self::History,
            self::Islam,
            self::Philosophy,
            self::Psychology,
            self::Romance,
            self::Travel => str($this->name)->headline(),
            self::BiographiesMemoirs,
            self::BuddhismHinduism,
            self::BusinessInvesting,
            self::ComputersTechnology,
            self::FictionLiterature,
            self::HomeArchitecture,
            self::HorrorParanormal,
            self::HumorComedy,
            self::LawTax,
            self::ReferenceDictionary,
            self::ReligionSpirituality,
            self::ScienceNature,
            self::SportsOutdoors => str($this->name)->headline()->replace(' ', ' & '),
            self::ArtMusicPhotography => 'Art, Music & Photography',
            self::ChildrenAge03 => 'Children Age 0-3',
            self::ChildrenAge47 => 'Children Age 4-7',
            self::ChildrenAge812 => 'Children Age 8-12',
            self::ComicsGraphicNovels => 'Comics & Graphic Novels',
            self::EducationTestPreparation => 'Education & Test Preparation',
            self::FoodDrinkCookbook => 'Food, Drink & Cookbook',
            self::HealthFitnessWellness => 'Health, Fitness & Wellness',
            self::MangaManhuaManhwa => 'Manga, Manhua & Manhwa',
            self::MotivationSelfHelp => 'Motivation & Self-Help',
            self::MysteryThrillerSuspense => 'Mystery, Thriller & Suspense',
            self::NonFiction => 'Non-fiction',
            self::ParentingRelationships => 'Parenting & Relationships',
            self::PoemShortStory => 'Poem & Short Story',
            self::PoliticsAffairsSocialSciences => 'Politics, Affairs & Social Sciences',
            self::ProfessionalEngineeringTechnical => 'Professional, Engineering & Technical',
            self::ScienceFictionFantasy => 'Science Fiction & Fantasy',
            self::TeenYoungAdultFiction => 'Teen & Young Adult Fiction',
        };
    }
}
