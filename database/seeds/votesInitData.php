<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
//use Illuminate\Database\Eloquent\Model;
use App\Vote;
use App\Tag;
use App\MyTag;
use App\TagDetail;
use App\Taggable as MyTaggable;
//use Spatie\Tags\Tag as SpatieTag;
use App\Tag as SpatieTag;
//use Spatie\Tags\HasTags;
use App\HasTags;

class votesInitData extends Seeder
{
    use HasTags;
    private $votes_tag_type= null;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // SOURCE http://laffgaff.com/easy-trivia-questions-and-answers/
        $this->votes_tag_type= with(new MyTag)->getVotesTagType();
        $lNewVote= Vote::create([
            'id'               => 1,
            'name'             => 'To be or not to be ?',
            'slug'             => 'to-be-or-not-to-be',
            'description'      => 'Still trying to find an answer. is the opening phrase of a soliloquy spoken by <i><i>Prince <i>Hamlet</i></i></i> in the so-called "nunnery scene" of <i><i>William Shakespeare</i>\'s</i> play <i>Hamlet</i>. Act III, Scene I.Though it is called a soliloquy <i>Hamlet</i> is not alone when he makes this speech because Ophelia is on stage pretending to read while waiting for <i>Hamlet</i> to notice her, and Claudius and Polonius, who have placed Ophelia in <i><i>Hamlet</i>\'s</i> way in order to overhear their conversation and find out if <i>Hamlet</i> is really mad or only pretending, have concealed themselves. Even so, <i>Hamlet</i> seems to consider himself alone and there is no indication that the others on stage hear him before he addresses Ophelia. In the speech, <i>Hamlet</i> contemplates death and suicide, bemoaning the pain and unfairness of life but acknowledging that the alternative might be worse. The meaning of the speech is heavily debated but seems clearly concerned with <i><i>Hamlet</i>\'s</i> hesitation to avenge his father\'s murder (discovered in Act I) by his uncle Claudius.
 Lorem  ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt...',
            'creator_id'       => 3,
            'vote_category_id' => 1,   // Classic literature
            'is_quiz'          => false,
            'is_homepage'      => true,
            'ordering'         => 1,
            'status'           => 'A',
            'meta_description' => 'William Shakespeare with eternal question',
            'meta_keywords'    => ['Hamlet', 'William Shakespeare', 'Theater'],
            'image'            => 'tobe.png',
        ]);
        $lNewVote->attachTag(SpatieTag::findOrCreate('Hamlet', $this->votes_tag_type));
        TagDetail::create([
            'tag_id'=>1,
            'image'=>'hamlet.jpg',
            'description'=>'The Tragedy of <i><i>Hamlet</i>, Prince of Denmark</i>, often shortened to <i>Hamlet</i> (/ˈhæmlɪt/), is a tragedy written by <i><i>William Shakespeare</i></i> at an uncertain date between 1599 and 1602. Set in Denmark, the play dramatises the revenge <i>Prince <i>Hamlet</i></i> is called to wreak upon his uncle, Claudius, by the ghost of <i><i>Hamlet</i>\'s</i> father, King <i>Hamlet</i>. Claudius had murdered his own brother and seized the throne, also marrying his deceased brother\'s widow.

<i>Hamlet</i> is Shakespeare\'s longest play, and is considered among the most powerful and influential works of world literature, with a story capable of "seemingly endless retelling and adaptation by others". It was one of Shakespeare\'s most popular works during his lifetime, and still ranks among his most performed, topping the performance list of the Royal Shakespeare Company and its predecessors in Stratford-upon-Avon since 1879. It has inspired many other writers—from Johann Wolfgang von Goethe and Charles Dickens to James Joyce and Iris Murdoch—and has been described as "the world\'s most filmed story after Cinderella".

The story of Shakespeare\'s <i>Hamlet</i> was derived from the legend of Amleth, preserved by 13th-century chronicler Saxo Grammaticus in his Gesta Danorum, as subsequently retold by the 16th-century scholar François de Belleforest. Shakespeare may also have drawn on an earlier Elizabethan play known today as the Ur-<i>Hamlet</i>, though some scholars believe he himself wrote the Ur-<i>Hamlet</i>, later revising it to create the version of <i>Hamlet</i> we now have. He almost certainly wrote his version of the title role for his fellow actor, Richard Burbage, the leading tragedian of Shakespeare\'s time. In the 400 years since its inception, the role has been performed by numerous highly acclaimed actors in each successive century.

Three different early versions of the play are extant: the First Quarto (Q1, 1603); the Second Quarto (Q2, 1604); and the First Folio (F1, 1623). Each version includes lines and entire scenes missing from the others. The play\'s structure and depth of characterisation have inspired much critical scrutiny. One such example is the centuries-old debate about <i><i>Hamlet</i>\'s</i> hesitation to kill his uncle, which some see as merely a plot device to prolong the action, but which others argue is a dramatisation of the complex philosophical and ethical issues that surround cold-blooded murder, calculated revenge, and thwarted desire. More recently, psychoanalytic critics have examined <i><i>Hamlet</i>\'s</i> unconscious desires, while feminist critics have re-evaluated and attempted to rehabilitate the often maligned characters of Ophelia and Gertrude.',

            'meta_description'   => 'The Tragedy of Hamlet, Prince of Denmark, is a tragedy written by William Shakespeare',
            'meta_keywords'      => json_encode(['Hamlet', 'William Shakespeare', 'Theater']),

        ]);

        $lNewVote->attachTag(SpatieTag::findOrCreate('William Shakespeare', $this->votes_tag_type));
        TagDetail::create([
            'tag_id'=>2,
            'image'=>'shakespeare.jpg',
            'description'=>'<i>William Shakespeare</i> (26 April 1564 (baptised) – 23 April 1616)[a] was an English poet, playwright and actor, widely regarded as both the greatest writer in the English language and the world\'s pre-eminent dramatist. He is often called England\'s national poet and the "Bard of Avon". His extant works, including collaborations, consist of approximately 39 plays,[c] 154 sonnets, two long narrative poems, and a few other verses, some of uncertain authorship. His plays have been translated into every major living language and are performed more often than those of any other playwright.

Shakespeare was born and raised in Stratford-upon-Avon, Warwickshire. At the age of 18, he married Anne Hathaway, with whom he had three children: Susanna and twins Hamnet and Judith. Sometime between 1585 and 1592, he began a successful career in London as an actor, writer, and part-owner of a playing company called the Lord Chamberlain\'s Men, later known as the King\'s Men. At age 49 (around 1613), he appears to have retired to Stratford, where he died three years later. Few records of Shakespeare\'s private life survive; this has stimulated considerable speculation about such matters as his physical appearance, his sexuality, his religious beliefs, and whether the works attributed to him were written by others. Such theories are often criticised for failing to adequately note the fact that few records survive of most commoners of the period.

Shakespeare produced most of his known works between 1589 and 1613. His early plays were primarily comedies and histories and are regarded as some of the best work ever produced in these genres. Then, until about 1608, he wrote mainly tragedies, among them <i>Hamlet</i>, Othello, King Lear, and Macbeth, all considered to be among the finest works in the English language. In the last phase of his life, he wrote tragicomedies (also known as romances) and collaborated with other playwrights.

Many of his plays were published in editions of varying quality and accuracy in his lifetime. However, in 1623, two fellow actors and friends of Shakespeare\'s, John Heminges and Henry Condell, published a more definitive text known as the First Folio, a posthumous collected edition of Shakespeare\'s dramatic works that included all but two of the plays now recognised as his. The volume was prefaced with a poem by Ben Jonson, in which the poet presciently hails the playwright in a now-famous quote as "not of an age, but for all time".

Throughout the 20th and 21st centuries, Shakespeare\'s works have been continually adapted and rediscovered by new movements in scholarship and performance. His plays remain highly popular and are constantly studied, performed, and reinterpreted through various cultural and political contexts around the world.',

            'meta_description'   => 'William Shakespeare was an English poet, playwright and actor',
            'meta_keywords'      => json_encode(['William Shakespeare', 'Theater']),

        ]);

        $lNewVote->attachTag(SpatieTag::findOrCreate('Drama', $this->votes_tag_type));
        TagDetail::create([
            'tag_id'=>3,
            'image'=>'drama.png',
            'description'=>'In reference to film and television, drama is a genre of narrative fiction (or semi-fiction) intended to be more serious than humorous in tone. Drama of this kind is usually qualified with additional terms that specify its particular subgenre, such as "police crime drama", "political drama", "legal drama", "historical period drama", "domestic drama", or "comedy-drama". These terms tend to indicate a particular setting or subject-matter, or else they qualify the otherwise serious tone of a drama with elements that encourage a broader range of moods.

All forms of cinema or television that involve fictional stories are forms of drama in the broader sense if their storytelling is achieved by means of actors who represent (mimesis) characters. In this broader sense, drama is a mode distinct from novels, short stories, and narrative poetry or songs. In the modern era before the birth of cinema or television, "drama" came to be used within the theatre as a generic term to describe a type of play that was neither a comedy nor a tragedy. It is this narrower sense that the film and television industries, along with film studies, adopted. "Radio drama" has been used in both senses—originally transmitted in a live performance, it has also been used to describe the more high-brow and serious end of the dramatic output of radio.',

            'meta_description'   => 'Drama is a genre of narrative fiction intended to be more serious than humorous in tone',
            'meta_keywords'      => json_encode(['Drama']),

        ]);

        $lNewVote->attachTag(SpatieTag::findOrCreate('Theater', $this->votes_tag_type));
        TagDetail::create([
            'tag_id'=>4,
            'image'=>'theater.jpg',
            'description'=>'Theatre or theater is a collaborative form of fine art that uses live performers, typically actors or actresses, to present the experience of a real or imagined event before a live audience in a specific place, often a stage. The performers may communicate this experience to the audience through combinations of gesture, speech, song, music, and dance. Elements of art, such as painted scenery and stagecraft such as lighting are used to enhance the physicality, presence and immediacy of the experience. The specific place of the performance is also named by the word "theatre" as derived from the Ancient Greek θέατρον (théatron, "a place for viewing"), itself from θεάομαι (theáomai, "to see", "to watch", "to observe").

Modern Western theatre comes, in large measure, from the theatre of ancient Greece, from which it borrows technical terminology, classification into genres, and many of its themes, stock characters, and plot elements. Theatre artist Patrice Pavis defines theatricality, theatrical language, stage writing and the specificity of theatre as synonymous expressions that differentiate theatre from the other performing arts, literature and the arts in general.

Modern theatre includes performances of plays and musical theatre. The art forms of ballet and opera are also theatre and use many conventions such as acting, costumes and staging. They were influential to the development of musical theatre; see those articles for more information.',
        ]);


        $lNewVote= Vote::create([
            'id'               => 2,
            'name'             => 'Who Framed Roger Rabbit ?',
            'slug'             => 'who-framed-roger-rabbit',
            'description'      => 'Who Framed Roger Rabbit is a 1988 American live-action/animated fantasy film directed by <i>Robert Zemeckis</i>, produced by Frank Marshall and Robert Watts, and written by Jeffrey Price and Peter S. Seaman. The film is based on Gary K. Wolf\'s 1981 novel Who Censored Roger Rabbit? The film stars Bob Hoskins, Christopher Lloyd, Charles Fleischer, Stubby Kaye, and Joanna Cassidy. Combining live-action and animation, the film is set in <b>Hollywood</b> during the late 1940s, where animated characters and people co-exist. The story follows Eddie Valiant, a private detective who must exonerate "Toon" (i.e. cartoon character) Roger Rabbit, who is accused of murdering a wealthy businessman.
            Lorem  ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt...
            Lorem  ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt...',
            'creator_id'       => 5,
            'vote_category_id' => 2,  // Movie&Cartoons
            'is_quiz'          => true,
            'is_homepage'      => true,
            'ordering'         => 1,
            'status'           => 'A',
            'meta_description' => ' American live-action/animated fantasy film directed by Robert Zemeckis',
            'meta_keywords'    => ['Roger Rabbit', 'live-action/animated fantasy', 'Robert Zemeckis'],
            'image'            => 'title.jpg',

        ]);
        $lNewVote->attachTag(SpatieTag::findOrCreate('Cartoon', $this->votes_tag_type));
        TagDetail::create([
            'tag_id' => 5,
            'image'  => 'cartoon.png',
            'description'=>'A cartoon is a type of illustration, possibly animated, typically in a non-realistic or semi-realistic style. The specific meaning has evolved over time, but the modern usage usually refers to either: an image or series of images intended for satire, caricature, or humor; or a motion picture that relies on a sequence of illustrations for its animation. Someone who creates cartoons in the first sense is called a cartoonist, and in the second sense they are usually called an animator.

The concept originated in the Middle Ages, and first described a preparatory drawing for a piece of art, such as a painting, fresco, tapestry, or stained glass window. In the 19th century, it came to refer – ironically at first – to humorous illustrations in magazines and newspapers. In the early 20th century, it began to refer to animated films which resembled print cartoons.',

            'meta_description'   => '',
            'meta_keywords'      => json_encode(['live-action', 'animated fantasy']),

        ]);

        $lNewVote->attachTag(SpatieTag::findOrCreate('Animated fantasy', $this->votes_tag_type));
        TagDetail::create([
            'tag_id'=>6,
            'image'=>'tranimated-fantasy.jpg',
            'description'=>'Animated fantasy Lorem  ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt... Lorem  ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt... Lorem  ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt... Lorem  ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt... Lorem  ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt...',

            'meta_description'   => 'Animated fantasy Lorem  ipsum dolor sit amet',
            'meta_keywords'      => json_encode(['Animated fantasy']),

        ]);



        $lNewVote->attachTag(SpatieTag::findOrCreate('Hollywood', $this->votes_tag_type));
        TagDetail::create([
            'tag_id' => 7,
            'image'  => 'hollywood.jpg',
            'description'=>'<b>Hollywood</b> is a neighborhood in the central region of Los Angeles, California, notable as the home of the U.S. film industry, including several of its historic studios. Its name has come to be a shorthand reference for the industry and the people associated with it.

<b>Hollywood</b> was a small community in 1870 and was incorporated as a municipality in 1903. It was consolidated with the city of Los Angeles in 1910 and soon thereafter a prominent film industry emerged, eventually becoming the most recognizable film industry in the world.',

            'meta_description'   => 'Hollywood is a neighborhood in the central region of Los Angeles, California, notable as the home of the U.S. film industry',
            'meta_keywords'      => json_encode(['Hollywood']),

        ]);


        $lNewVote->attachTag(SpatieTag::findOrCreate('Animated fantasy', $this->votes_tag_type));

        $lNewVote->attachTag(SpatieTag::findOrCreate('Film', $this->votes_tag_type));
        TagDetail::create([
            'tag_id'=> 8,
            'image'=>'Berlin_Wintergarten.jpg',
            'description'=>'A film, also called a movie, motion picture, moving picture, theatrical film, or photoplay, is a series of still images that, when shown on a screen, create the illusion of moving images. (See the glossary of motion picture terms.)

This optical illusion causes the audience to perceive continuous motion between separate objects viewed in rapid succession. The process of filmmaking is both an art and an industry. A film is created by photographing actual scenes with a motion-picture camera, by photographing drawings or miniature models using traditional animation techniques, by means of CGI and computer animation, or by a combination of some or all of these techniques, and other visual effects.

The word "cinema", short for cinematography, is often used to refer to filmmaking and the film industry, and to the art of filmmaking itself. The contemporary definition of cinema is the art of simulating experiences to communicate ideas, stories, perceptions, feelings, beauty or atmosphere by the means of recorded or programmed moving images along with other sensory stimulations.

Films were originally recorded onto plastic film through a photochemical process and then shown through a movie projector onto a large screen. Contemporary films are now often fully digital through the entire process of production, distribution, and exhibition, while films recorded in a photochemical form traditionally included an analogous optical soundtrack (a graphic recording of the spoken words, music and other sounds that accompany the images which runs along a portion of the film exclusively reserved for it, and is not projected).

Films are cultural artifacts created by specific cultures. They reflect those cultures, and, in turn, affect them. Film is considered to be an important art form, a source of popular entertainment, and a powerful medium for educating—or indoctrinating—citizens. The visual basis of film gives it a universal power of communication. Some films have become popular worldwide attractions through the use of dubbing or subtitles to translate the dialog into other languages.

The individual images that make up a film are called frames. In the projection of traditional celluloid films, a rotating shutter causes intervals of darkness as each frame, in turn, is moved into position to be projected, but the viewer does not notice the interruptions because of an effect known as persistence of vision, whereby the eye retains a visual image for a fraction of a second after its source disappears. The perception of motion is due to a psychological effect called the phi phenomenon.

The name "film" originates from the fact that photographic film (also called film stock) has historically been the medium for recording and displaying motion pictures. Many other terms exist for an individual motion-picture, including picture, picture show, moving picture, photoplay, and flick. The most common term in the United States is movie, while in Europe film is preferred. Common terms for the field in general include the big screen, the silver screen, the movies, and cinema; the last of these is commonly used, as an overarching term, in scholarly texts and critical essays. In early years, the word sheet was sometimes used instead of screen.',

            'meta_description'   => '',
            'meta_keywords'      => json_encode(['Film']),

        ]);


        $lNewVote->attachTag(SpatieTag::findOrCreate('Movie director', $this->votes_tag_type));
        TagDetail::create([
            'tag_id'=> 9,
            'image'=>'Alfred_Hitchcock.jpg',
            'description'=>'A film director is a person who directs the making of a film. A film director controls a film\'s artistic and dramatic aspects and visualizes the screenplay (or script) while guiding the technical crew and actors in the fulfillment of that vision. The director has a key role in choosing the cast members, production design, and the creative aspects of filmmaking. Under European Union law, the director is viewed as the author of the film.

The film director gives direction to the cast and crew and creates an overall vision through which a film eventually becomes realized, or noticed. Directors need to be able to mediate differences in creative visions and stay within the boundaries of the film\'s budget.

There are many pathways to becoming a film director. Some film directors started as screenwriters, cinematographers, film editors or actors. Other film directors have attended a film school. Directors use different approaches. Some outline a general plotline and let the actors improvise dialogue, while others control every aspect, and demand that the actors and crew follow instructions precisely. Some directors also write their own screenplays or collaborate on screenplays with long-standing writing partners. Some directors edit or appear in their films, or compose the music score for their films.',

            'meta_description'   => '',
            'meta_keywords'      => json_encode(['Movie director']),

        ]);


        $lNewVote->attachTag(SpatieTag::findOrCreate('Robert Zemeckis', $this->votes_tag_type));
        TagDetail::create([
            'tag_id'=> 10,
            'image'=>'Robert_Zemeckis.jpg',
            'description'=>'Robert Lee Zemeckis (/zəˈmɛkɪs/; born May 14, 1952) is an American filmmaker frequently credited as an innovator in visual effects. He first came to public attention in the 1980s as the director of Romancing the Stone (1984) and the science-fiction comedy Back to the Future film trilogy, as well as the live-action/animated comedy Who Framed Roger Rabbit (1988). In the 1990s he directed Death Becomes Her and then diversified into more dramatic fare, including 1994\'s Forrest Gump, for which he won an Academy Award for Best Director. The film itself won Best Picture. The films he has directed have ranged across a wide variety of genres, for both adults and families.

Zemeckis\'s films are characterized by an interest in state-of-the-art special effects, including the early use of the insertion of computer graphics into live-action footage in Back to the Future Part II (1989) and Forrest Gump, and the pioneering performance capture techniques seen in The Polar Express (2004), Monster House (2006), Beowulf (2007) and A Christmas Carol (2009). Though Zemeckis has often been pigeonholed as a director interested only in special effects, his work has been defended by several critics including David Thomson, who wrote that "No other contemporary director has used special effects to more dramatic and narrative purpose."',

            'meta_description'   => '',
            'meta_keywords'      => json_encode(['Robert Zemeckis']),

        ]);

        $lNewVote= Vote::create([
            'id'               => 3,
            'name'             => 'How many weeks in a year ?',
            'slug'             => 'how-many-weeks-in-a-year',
            'description'      => 'A normal year in the \'modern\' calendar has 365 days, which, when divided by 7 (Monday, Tuesday, Wednesday, Thursday, Friday, Saturday, Sunday) equals 52.1428571 weeks.
Every four years, there is a leap year*, adding an extra day into the calendar (pity those 17 year olds born on February 29th 2000, who have only had 4 true birthdays). In this case, the calculation is 366 divided by 7, which equals 52.2857, so it\'s still just 52 weeks.
This is all based upon the Gregorian calendar (introduced back in 1582), which cycles every 400 years. If you want to go a step further and work out the average number of weeks in a year across the full Gregorian calendar, you\'ll find that a year works out at 365.2425 days. When divided by 7 it gives you a total of 52.1775 weeks.',
            'creator_id'       => 1,
            'vote_category_id' => 3, // Earth&World
            'is_quiz'          => true,
            'is_homepage'      => false,
            'ordering'         => 3,
            'status'           => 'A',
            'meta_description' => '',
            'meta_keywords'    => ['days of week', '365 days', 'calendar'],
            'image'            => null,
        ]);
        $lNewVote->attachTag(SpatieTag::findOrCreate('Calendar', $this->votes_tag_type));
        TagDetail::create([
            'tag_id'=> 11,
            'image'=> 'calender.jpg',
            'description'=>'A calendar is a system of organizing days for social, religious, commercial or administrative purposes. This is done by giving names to periods of time, typically days, weeks, months and years. A date is the designation of a single, specific day within such a system. A calendar is also a physical record (often paper) of such a system. A calendar can also mean a list of planned events, such as a court calendar or a partly or fully chronological list of documents, such as a calendar of wills.

Periods in a calendar (such as years and months) are usually, though not necessarily, synchronised with the cycle of the sun or the moon. The most common type of pre-modern calendar was the lunisolar calendar, a lunar calendar that occasionally adds one intercalary month to remain synchronised with the solar year over the long term.

The term calendar is taken from calendae, the term for the first day of the month in the Roman calendar, related to the verb calare "to call out", referring to the "calling" of the new moon when it was first seen. Latin calendarium meant "account book, register" (as accounts were settled and debts were collected on the calends of each month). The Latin term was adopted in Old French as calendier and from there in Middle English as calender by the 13th century (the spelling calendar is early modern).',

            'meta_description'   => 'A calendar is a system of organizing days for social, religious, commercial or administrative purposes',
            'meta_keywords'      => json_encode(['Calendar']),

        ]);

        $lNewVote->attachTag(SpatieTag::findOrCreate('Year', $this->votes_tag_type));
        TagDetail::create([
            'tag_id'=> 12,
            'image'=>'',
            'description'=>'A year is the orbital period of the Earth moving in its orbit around the Sun. Due to the Earth\'s axial tilt, the course of a year sees the passing of the seasons, marked by change in weather, the hours of daylight, and, consequently, vegetation and soil fertility. The current year is 2018.

In temperate and subpolar regions around the planet, four seasons are generally recognized: spring, summer, autumn, and winter. In tropical and subtropical regions, several geographical sectors do not present defined seasons; but in the seasonal tropics, the annual wet and dry seasons are recognized and tracked.

A calendar year is an approximation of the number of days of the Earth\'s orbital period as counted in a given calendar. The Gregorian calendar, or modern calendar, presents its calendar year to be either a common year of 365 days or a leap year of 366 days, as do the Julian calendars; see below. For the Gregorian calendar, the average length of the calendar year (the mean year) across the complete leap cycle of 400 years is 365.2425 days. The ISO standard ISO 80000-3, Annex C, supports the symbol a (for Latin annus) to represent a year of either 365 or 366 days. In English, the abbreviations y and yr are commonly used.

In astronomy, the Julian year is a unit of time; it is defined as 365.25 days of exactly 86,400 seconds (SI base unit), totalling exactly 31,557,600 seconds in the Julian astronomical year.

The word year is also used for periods loosely associated with, but not identical to, the calendar or astronomical year, such as the seasonal year, the fiscal year, the academic year, etc. Similarly, year can mean the orbital period of any planet; for example, a Martian year and a Venusian year are examples of the time a planet takes to transit one complete orbit. The term can also be used in reference to any long period or cycle, such as the Great Year.',

            'meta_description'   => 'A year is the orbital period of the Earth moving in its orbit around the Sun',
            'meta_keywords'      => json_encode(['Year']),

        ]);

        $lNewVote= Vote::create([
            'id'               => 4,
            'name'             => 'Which fictional city is the home of Batman ?',
            'slug'             => 'which-fictional-city-is-the-home-of-batman',
            'description'      => 'You must to know something about Batman...
            There are thousands of comic book characters who have influenced individuals and even larger audiences, but few have become legendary. Only a handful of comic characters have been able to transcend the nerd realm and enter the minds of mainstream audiences.
Of course, one of this handful is Batman, one of the most iconic superheroes of all-time, rivaled only by his DC counterpart Superman, who he\'ll be battling soon in the upcoming Batman V Superman: Dawn of Justice. From the countless incarnations we’ve seen in comics, television, and movies, every Batman left a mark on entertainment.
However in order to understand the character and really grasp his motivations, actions, and importance, we must look at some of the definitive moments in Batman\'s history. Having been around for 76 years, there is a lot to siphon through, but here we go!',
            'creator_id'       => 4,
            'vote_category_id' => 2,  // Movie&Cartoons
            'is_quiz'          => true,
            'is_homepage'      => true,
            'ordering'         => 4,
            'status'           => 'A',
            'meta_description' => '',
            'meta_keywords'    => ['fictional city', 'Batman', 'Superman'],
            'image'            => 'city.jpg',
        ]);
        $lNewVote->attachTag(SpatieTag::findOrCreate('Hollywood', $this->votes_tag_type));
        $lNewVote->attachTag(SpatieTag::findOrCreate('Animated fantasy', $this->votes_tag_type));

        $lNewVote= Vote::create([
            'id'               => 5,
            'name'             => 'Who was known as the Maid of Orleans ?',
            'slug'             => 'who-was-known-as-the-maid-of-orleans',
            'description'      => 'Question about France history lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt  mollit anim id est laborum.',
            'creator_id'       => 3,
            'vote_category_id' => 4,   // History
            'is_quiz'          => true,
            'is_homepage'      => false,
            'ordering'         => 5,
            'status'           => 'I',
            'meta_description' => '',
            'meta_keywords'    => ['France history', 'Maid of Orleans'],
            'image'            => null,
        ]);
        $lNewVote->attachTag(SpatieTag::findOrCreate('France', $this->votes_tag_type));
        TagDetail::create([
            'tag_id'=> 13,
            'image' => 'Flag_of_France.png',
            'description'=>'France (French: [fʁɑ̃s]), officially the French Republic (French: République française; French pronunciation: ​[ʁepyblik fʁɑ̃sɛz]), is a country whose territory consists of metropolitan France in Western Europe and several overseas regions and territories.[XIII] The metropolitan area of France extends from the Mediterranean Sea to the English Channel and the North Sea, and from the Rhine to the Atlantic Ocean. The overseas territories include French Guiana in South America and several islands in the Atlantic, Pacific and Indian oceans. The country\'s 18 integral regions (five of which are situated overseas) span a combined area of 643,801 square kilometres (248,573 sq mi) and a total population of 67.3 million (as of October 2018). France, a sovereign state, is a unitary semi-presidential republic with its capital in Paris, the country\'s largest city and main cultural and commercial centre. Other major urban areas include Lyon, Marseille, Toulouse, Bordeaux, Lille and Nice.

During the Iron Age, what is now metropolitan France was inhabited by the Gauls, a Celtic people. Rome annexed the area in 51 BC, holding it until the arrival of Germanic Franks in 476, who formed the Kingdom of France. France emerged as a major European power in the Late Middle Ages following its victory in the Hundred Years\' War (1337 to 1453). During the Renaissance, French culture flourished and a global colonial empire was established, which by the 20th century would become the second largest in the world. The 16th century was dominated by religious civil wars between Catholics and Protestants (Huguenots). France became Europe\'s dominant cultural, political, and military power in the 17th century under Louis XIV. In the late 18th century, the French Revolution overthrew the absolute monarchy, established one of modern history\'s earliest republics, and saw the drafting of the Declaration of the Rights of Man and of the Citizen, which expresses the nation\'s ideals to this day.

In the 19th century, Napoleon took power and established the First French Empire. His subsequent Napoleonic Wars shaped the course of continental Europe. Following the collapse of the Empire, France endured a tumultuous succession of governments culminating with the establishment of the French Third Republic in 1870. France was a major participant in World War I, from which it emerged victorious, and was one of the Allies in World War II, but came under occupation by the Axis powers in 1940. Following liberation in 1944, a Fourth Republic was established and later dissolved in the course of the Algerian War. The Fifth Republic, led by Charles de Gaulle, was formed in 1958 and remains today. Algeria and nearly all the other colonies became independent in the 1960s and typically retained close economic and military connections with France.

France has long been a global centre of art, science, and philosophy. It hosts the world\'s fourth-largest number of UNESCO World Heritage Sites and is the leading tourist destination, receiving around 83 million foreign visitors annually. France is a developed country with the world\'s seventh-largest economy by nominal GDP, and tenth-largest by purchasing power parity. In terms of aggregate household wealth, it ranks fourth in the world. France performs well in international rankings of education, health care, life expectancy, and human development. France is considered a great power in global affairs, being one of the five permanent members of the United Nations Security Council with the power to veto and an official nuclear-weapon state. It is a leading member state of the European Union and the Eurozone, and a member of the Group of 7, North Atlantic Treaty Organization (NATO), Organisation for Economic Co-operation and Development (OECD), the World Trade Organization (WTO), and La Francophonie.',

            'meta_description'   => 'France (French: officially the French Republic, is a country whose territory consists of metropolitan France in Western Europe and several overseas regions and territories',
            'meta_keywords'      => json_encode(['France']),

        ]);

        $lNewVote->attachTag(SpatieTag::findOrCreate('Middle Ages', $this->votes_tag_type));
        TagDetail::create([
            'tag_id' => 14,
            'image'  => '350px-Europe_map_450.png',
            'description'=>'The Cross of Mathilde, a crux gemmata made for Mathilde, Abbess of Essen (973–1011), who is shown kneeling before the Virgin and Child in the enamel plaque. The figure of Christ is slightly later. Probably made in Cologne or Essen, the cross demonstrates several medieval techniques: cast figurative sculpture, filigree, enamelling, gem polishing and setting, and the reuse of Classical cameos and engraved gems.
In the history of Europe, the Middle Ages (or Medieval period) lasted from the 5th to the 15th century. It began with the fall of the Western Roman Empire and merged into the Renaissance and the Age of Discovery. The Middle Ages is the middle period of the three traditional divisions of Western history: classical antiquity, the medieval period, and the modern period. The medieval period is itself subdivided into the Early, High, and Late Middle Ages.

Population decline, counterurbanisation, invasion, and movement of peoples, which had begun in Late Antiquity, continued in the Early Middle Ages. The large-scale movements of the Migration Period, including various Germanic peoples, formed new kingdoms in what remained of the Western Roman Empire. In the 7th century, North Africa and the Middle East—once part of the Byzantine Empire—came under the rule of the Umayyad Caliphate, an Islamic empire, after conquest by Muhammad\'s successors. Although there were substantial changes in society and political structures, the break with classical antiquity was not complete. The still-sizeable Byzantine Empire, Rome\'s direct continuation, survived in the Eastern Mediterranean and remained a major power. The empire\'s law code, the Corpus Juris Civilis or "Code of Justinian", was rediscovered in Northern Italy in 1070 and became widely admired later in the Middle Ages. In the West, most kingdoms incorporated the few extant Roman institutions. Monasteries were founded as campaigns to Christianise pagan Europe continued. The Franks, under the Carolingian dynasty, briefly established the Carolingian Empire during the later 8th and early 9th century. It covered much of Western Europe but later succumbed to the pressures of internal civil wars combined with external invasions: Vikings from the north, Magyars from the east, and Saracens from the south.

During the High Middle Ages, which began after 1000, the population of Europe increased greatly as technological and agricultural innovations allowed trade to flourish and the Medieval Warm Period climate change allowed crop yields to increase. Manorialism, the organisation of peasants into villages that owed rent and labour services to the nobles, and feudalism, the political structure whereby knights and lower-status nobles owed military service to their overlords in return for the right to rent from lands and manors, were two of the ways society was organised in the High Middle Ages. The Crusades, first preached in 1095, were military attempts by Western European Christians to regain control of the Holy Land from Muslims. Kings became the heads of centralised nation-states, reducing crime and violence but making the ideal of a unified Christendom more distant. Intellectual life was marked by scholasticism, a philosophy that emphasised joining faith to reason, and by the founding of universities. The theology of Thomas Aquinas, the paintings of Giotto, the poetry of Dante and Chaucer, the travels of Marco Polo, and the Gothic architecture of cathedrals such as Chartres are among the outstanding achievements toward the end of this period and into the Late Middle Ages.

The Late Middle Ages was marked by difficulties and calamities including famine, plague, and war, which significantly diminished the population of Europe; between 1347 and 1350, the Black Death killed about a third of Europeans. Controversy, heresy, and the Western Schism within the Catholic Church paralleled the interstate conflict, civil strife, and peasant revolts that occurred in the kingdoms. Cultural and technological developments transformed European society, concluding the Late Middle Ages and beginning the early modern period.',

            'meta_description'   => '',
            'meta_keywords'      => json_encode(['Middle Ages']),

        ]);


        $lNewVote= Vote::create([
            'id'               => 6,
            'name'             => 'Do you like design of this site ?',
            'slug'             => 'do-you-like-design-of-this-site',
            'description'      => 'Do you like design of this site ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt  mollit anim id est laborum.',
            'creator_id'       => 5,
            'vote_category_id' => 6,   // Miscellaneous
            'is_quiz'          => false,
            'is_homepage'      => true,
            'ordering'         => 6,
            'status'           => 'A',
            'meta_description' => '',
            'meta_keywords'    => ['design', 'responsive page'],
            'image'            => '',
        ]);


        $lNewVote= Vote::create([
            'id'               => 7,
            'name'             => 'Which Roman emperor supposedly fiddled while Rome burned ?',
            'slug'             => 'which-roman-emperor-supposedly-fiddled-while-rome-burned',
            'description'      => 'It was during the night of the 18 th July 64 AD, when a fire broke out in the merchant area of the city. Strong summer winds fanned the fire, with flames quickly spreading throughout the old dry wooden buildings of the city. According to the historian Tacitus, the fire raged for five day before it was finally brought under control. Of the fourteen districts of Rome, four were untouched, three were destroyed, and seven were heavily damaged. Tacitus was the only Roman writer alive during that period, apart from Pliny the Elder, who wrote about the fire. There is, however, an epistle, supposedly from Seneca the Younger to St. Paul, which states explicitly the damage done by the fire – according to him, only four blocks of insulae (a type of apartment building) and 132 private houses were damaged or destroyed. Still, one could question the motive of Seneca the Younger, as these figures were given in the context of the execution of Christians who were blamed for starting the fire. By showing that only a small amount of damage was inflicted by the fire, it would have highlighted the unjust punishment meted out against the Christians.
             lorem  ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt  mollit anim id est laborum.
             lorem  ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt  mollit anim id est laborum.
             lorem  ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt  mollit anim id est laborum.
             lorem  ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt  mollit anim id est laborum.
             ',
            'creator_id'       => 2,
            'vote_category_id' => 4,    // History
            'is_quiz'          => true,
            'is_homepage'      => false,
            'ordering'         => 7,
            'status'           => 'N',
            'meta_description' => '',
            'meta_keywords'    => ['Rome burned', 'Roman emperor'],
            'image'            => 'rome_burned.jpg',
        ]);
        $lNewVote->attachTag(SpatieTag::findOrCreate('Ancient Rome', $this->votes_tag_type));
        TagDetail::create([
            'tag_id' => 15,
            'image'  => 'Roman_Republic_Empire_map.gif',
            'description'=>'In historiography, ancient Rome is Roman civilization from the founding of the city of Rome in the 8th century BC to the collapse of the Western Roman Empire in the 5th century AD, encompassing the Roman Kingdom, Roman Republic and Roman Empire until the fall of the western empire. The term is sometimes used to refer only to the kingdom and republic periods, excluding the subsequent empire.

The civilization began as an Italic settlement in the Italian peninsula, dating from the 8th century BC, that grew into the city of Rome and which subsequently gave its name to the empire over which it ruled and to the widespread civilisation the empire developed. The Roman empire expanded to become one of the largest empires in the ancient world, though still ruled from the city, with an estimated 50 to 90 million inhabitants (roughly 20% of the world\'s population) and covering 5.0 million square kilometres at its height in AD 117.

In its many centuries of existence, the Roman state evolved from a monarchy to a Classical Republic and then to an increasingly autocratic empire. Through conquest and assimilation, it eventually dominated the Mediterranean region, Western Europe, Asia Minor, North Africa, and parts of Northern and Eastern Europe. It is often grouped into classical antiquity together with ancient Greece, and their similar cultures and societies are known as the Greco-Roman world.

Ancient Roman civilisation has contributed to modern government, law, politics, engineering, art, literature, architecture, technology, warfare, religion, language, and society. Rome professionalised and expanded its military and created a system of government called res publica, the inspiration for modern republics such as the United States and France. It achieved impressive technological and architectural feats, such as the construction of an extensive system of aqueducts and roads, as well as the construction of large monuments, palaces, and public facilities.

By the end of the Republic (27 BC), Rome had conquered the lands around the Mediterranean and beyond: its domain extended from the Atlantic to Arabia and from the mouth of the Rhine to North Africa. The Roman Empire emerged with the end of the Republic and the dictatorship of Augustus Caesar. 721 years of Roman-Persian Wars started in 92 BC with their first war against Parthia. It would become the longest conflict in human history, and have major lasting effects and consequences for both empires. Under Trajan, the Empire reached its territorial peak. Republican mores and traditions started to decline during the imperial period, with civil wars becoming a prelude common to the rise of a new emperor. Splinter states, such as the Palmyrene Empire, would temporarily divide the Empire during the crisis of the 3rd century.

Plagued by internal instability and attacked by various migrating peoples, the western part of the empire broke up into independent "barbarian" kingdoms in the 5th century. This splintering is a landmark historians use to divide the ancient period of universal history from the pre-medieval "Dark Ages" of Europe. The eastern part of the empire endured through the 5th century and remained a power throughout the "Dark Ages" and medieval times until its fall in 1453 AD. Although the citizens of the empire made no distinction, the empire is most commonly referred to as the "Byzantine Empire" by modern historians during the Middle Ages to differentiate between the state of antiquity and the nation it grew into.',

            'meta_description'   => '',
            'meta_keywords'      => json_encode(['Ancient Rome']),

        ]);

        $lNewVote->attachTag(SpatieTag::findOrCreate('Ancient History', $this->votes_tag_type));
        TagDetail::create([
            'tag_id' => 16,
            'image'  => 'Ancient_cities_of_Sumer.jpg',
            'description'=>'Ancient history as a term refers to the aggregate of past events from the beginning of writing and recorded human history and extending as far as the post-classical history. The phrase may be used either to refer to the period of time or the academic discipline.

The span of recorded history is roughly 5,000 years, beginning with Sumerian Cuneiform script, the oldest discovered form of coherent writing from the protoliterate period around the 30th century BC. Ancient History covers all continents inhabited by humans in the 3,000 BC - 500 AD period.

The broad term Ancient History is not to be confused with Classical Antiquity. The term classical antiquity is often used to refer to Western History in the Ancient Mediterranean from the beginning of recorded Greek history in 776 BC (First Olympiad). This roughly coincides with the traditional date of the founding of Rome in 753 BC, the beginning of the history of ancient Rome, and the beginning of the Archaic period in Ancient Greece.

The academic term "history" is additionally not to be confused with colloquial references to times past. History is fundamentally the study of the past through documents, and can be either scientific (archaeology) or humanistic (history through language).

Although the ending date of ancient history is disputed, some Western scholars use the fall of the Western Roman Empire in 476 AD (the most used), the closure of the Platonic Academy in 529 AD, the death of the emperor Justinian I in 565 AD, the coming of Islam or the rise of Charlemagne as the end of ancient and Classical European history. Outside of Europe the 450-500 time frame for the end of ancient times has had difficulty as a transition date from Ancient to Post-Classical times.

During the time period of \'Ancient History\' starting roughly from 3000 B.C world population was already exponentially increasing due to the Neolithic Revolution which was in full progress. According to HYDE estimates from the Netherlands world population increased exponentially in this period. At 10,000 BC in Prehistory world population had stood at 2 million, rising to 45 million by 3,000 B.C. By the rise of the Iron Age in 1,000 BC that population had risen to 72 million. By the end of the period in 500 AD world population stood possibly at 209 million.',

            'meta_description'   => 'Ancient Rome is Roman civilization from the founding of the city of Rome in the 8th century BC to the collapse of the Western Roman Empire in the 5th century AD',
            'meta_keywords'      => json_encode(['Ancient History']),

        ]);



        $lNewVote= Vote::create([
            'id'               => 8,
            'name'             => 'Which crime fighting cartoon dog has the initals “S.D.” on his collar ?',
            'slug'             => 'which-crime-fighting-cartoon-dog-has-the-initals-S-D-on-his-collar',
            'description'      => 'Which crime-fighting cartoon dog has the initals “S.D.” on his collar ... on the other hand, we denounce with righteous indignation and  dislike men who are so beguiled and demoralized by the charms of  pleasure of the moment, so blinded by desire, that they cannot foresee  the pain and trouble that are bound to ensue; and equal blame belongs to  those who fail in their duty through weakness of will, which is the  same as saying through shrinking from toil and pain. These cases are  perfectly simple and easy to distinguish. In a free hour, when our power  of choice is untrammelled and when nothing prevents our being able to  do what we like best, every pleasure is to be welcomed and every pain  avoided. But in certain circumstances and owing to the claims of duty or  the obligations of business it will frequently occur that pleasures  have to be repudiated and annoyances accepted. The wise man therefore  always holds in these matters to this principle of selection: he rejects  pleasures to secure other greater pleasures, or else he endures pains  to avoid worse pains. 
            on the other hand, we denounce with righteous indignation and  dislike men who are so beguiled and demoralized by the charms of  pleasure of the moment, so blinded by desire, that they cannot foresee  the pain and trouble that are bound to ensue; and equal blame belongs to  those who fail in their duty through weakness of will, which is the  same as saying through shrinking from toil and pain. These cases are  perfectly simple and easy to distinguish. In a free hour, when our power  of choice is untrammelled and when nothing prevents our being able to  do what we like best, every pleasure is to be welcomed and every pain  avoided. But in certain circumstances and owing to the claims of duty or  the obligations of business it will frequently occur that pleasures  have to be repudiated and annoyances accepted. The wise man therefore  always holds in these matters to this principle of selection: he rejects  pleasures to secure other greater pleasures, or else he endures pains  to avoid worse pains.
            on the other hand, we denounce with righteous indignation and  dislike men who are so beguiled and demoralized by the charms of  pleasure of the moment, so blinded by desire, that they cannot foresee  the pain and trouble that are bound to ensue; and equal blame belongs to  those who fail in their duty through weakness of will, which is the  same as saying through shrinking from toil and pain. These cases are  perfectly simple and easy to distinguish. In a free hour, when our power  of choice is untrammelled and when nothing prevents our being able to  do what we like best, every pleasure is to be welcomed and every pain  avoided. But in certain circumstances and owing to the claims of duty or  the obligations of business it will frequently occur that pleasures  have to be repudiated and annoyances accepted. The wise man therefore  always holds in these matters to this principle of selection: he rejects  pleasures to secure other greater pleasures, or else he endures pains  to avoid worse pains.',
            'creator_id'       => 2,
            'vote_category_id' => 2,  // Movie&Cartoons
            'is_quiz'          => true,
            'is_homepage'      => true,
            'ordering'         => 8,
            'status'           => 'A',
            'meta_description' => '',
            'meta_keywords'    => ['crime fighting', 'cartoon dog', 'Hollywood'],
            'image'            => 'cartoon-dog.jpg',
        ]);
        $lNewVote->attachTag(SpatieTag::findOrCreate('Hollywood', $this->votes_tag_type));
        $lNewVote->attachTag(SpatieTag::findOrCreate('Animated fantasy', $this->votes_tag_type));
        $lNewVote->attachTag(SpatieTag::findOrCreate('Cartoon', $this->votes_tag_type));


        $lNewVote= Vote::create([
            'id'               => 9,
            'name'             => 'Traditionally, how many Wonders of the World are there ?',
            'slug'             => 'traditionally-how-many-wonders-of-the-world-are-there',
            'description'      => 'The classic seven wonders were:
Colossus of Rhodes
Great Pyramid of Giza
Hanging Gardens of Babylon
Lighthouse of Alexandria
Mausoleum at Halicarnassus
Statue of Zeus at Olympia
Temple of Artemis at Ephesus
The only ancient world wonder that still exists is the Great Pyramid of Giza.

Lists from other eras
In the 19th and early 20th centuries, some writers wrote their own lists with names such as Wonders of the Middle Ages, Seven Wonders of the Middle Ages, Seven Wonders of the Medieval Mind, and Architectural Wonders of the Middle Ages. However, it is unlikely that these lists originated in the Middle Ages, because the word "medieval" was not invented until the Enlightenment-era, and the concept of a Middle Age did not become popular until the 16th century. Brewer\'s Dictionary of Phrase and Fable refers to them as "later list[s]", suggesting the lists were created after the Middle Ages.

Many of the structures on these lists were built much earlier than the Medieval Ages but were well known.

Catacombs of Kom el Shoqafa
Colosseum
Great Wall of China
Hagia Sophia
Leaning Tower of Pisa
Porcelain Tower of Nanjing
Stonehenge',
            'creator_id'       => 4,
            'vote_category_id' => 3,  // Earth&World
            'is_quiz'          => true,
            'is_homepage'      => false,
            'ordering'         => 9,
            'status'           => 'A',
            'meta_description' => '',
            'meta_keywords'    => ['Wonders of the World', 'Interesting Places'],
            'image'            => 'Seven-Wonders-of-the-World.png',
        ]);
        $lNewVote->attachTag(SpatieTag::findOrCreate('World', $this->votes_tag_type));
        TagDetail::create([
            'tag_id' => 17,
            'image'  => 'The_Earth.jpg',
            'description'=>'The world is the planet Earth and all life upon it, including human civilization. In a philosophical context, the "world" is the whole of the physical Universe, or an ontological world (the "world" of an individual). In a theological context, the world is the material or the profane sphere, as opposed to the celestial, spiritual, transcendent or sacred spheres. "End of the world" scenarios refer to the end of human history, often in religious contexts.

The history of the world is commonly understood as spanning the major geopolitical developments of about five millennia, from the first civilizations to the present. In terms such as world religion, world language, world government, and world war, the term world suggests an international or intercontinental scope without necessarily implying participation of every part of the world.

The world population is the sum of all human populations at any time; similarly, the world economy is the sum of the economies of all societies or countries, especially in the context of globalization. Terms such as "world championship", "gross world product", and "world flags" imply the sum or combination of all sovereign states.',

            'meta_description'   => 'The world is the planet Earth and all life upon it, including human civilization',
            'meta_keywords'      => json_encode(['World']),

        ]);

        $lNewVote->attachTag(SpatieTag::findOrCreate('Interesting Places', $this->votes_tag_type));
        TagDetail::create([
            'tag_id' => 18,
            'image'  => 'interesting_places.png',
            'description'=>'Looking for a more unusual travel destination this year? Check out these photos of some unbelievably amazing places in the world; we challenge you to read on without reaching for your passport...',

            'meta_description'   => '',
            'meta_keywords'      => json_encode(['Interesting Places']),

        ]);

        $lNewVote= Vote::create([
            'id'               => 10,
            'name'             => 'What is the name of the fairy in Peter Pan ?',
            'slug'             => 'what-is-the-name-of-the-fairy-in-peter-pan',
            'description'      => 'What is the name of the fairy in Peter Pan lorem  ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt  mollit anim id est laborum.
lorem  ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt  mollit anim id est laborum. 
lorem  ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt  mollit anim id est laborum.        ',
            'creator_id'       => 1,
            'vote_category_id' => 1, // Classic literature
            'is_quiz'          => true,
            'is_homepage'      => true,
            'ordering'         => 10,
            'status'           => 'A',
            'meta_description' => '',
            'meta_keywords'    => ['fairy', 'Peter Pan', 'Fictional characters'],
            'image'            => 'Peter_Pan,_by_Oliver_Herford,_1907.png',
        ]);
        $lNewVote->attachTag(SpatieTag::findOrCreate('Fictional characters', $this->votes_tag_type));
        TagDetail::create([
            'tag_id' => 19,
            'image'  => 'fictional_characters.jpg',
            'description'=>'A character (sometimes known as a fictional character) is a person or other being in a narrative (such as a novel, play, television series, film, or video game). The character may be entirely fictional or based on a real-life person, in which case the distinction of a "fictional" versus "real" character may be made. Derived from the ancient Greek word χαρακτήρ, the English word dates from the Restoration, although it became widely used after its appearance in Tom Jones in 1749. From this, the sense of "a part played by an actor" developed. Character, particularly when enacted by an actor in the theatre or cinema, involves "the illusion of being a human person". In literature, characters guide readers through their stories, helping them to understand plots and ponder themes. Since the end of the 18th century, the phrase "in character" has been used to describe an effective impersonation by an actor. Since the 19th century, the art of creating characters, as practiced by actors or writers, has been called characterisation.

A character who stands as a representative of a particular class or group of people is known as a type. Types include both stock characters and those that are more fully individualised. The characters in Henrik Ibsen\'s Hedda Gabler (1891) and August Strindberg\'s Miss Julie (1888), for example, are representative of specific positions in the social relations of class and gender, such that the conflicts between the characters reveal ideological conflicts.

The study of a character requires an analysis of its relations with all of the other characters in the work. The individual status of a character is defined through the network of oppositions (proairetic, pragmatic, linguistic, proxemic) that it forms with the other characters. The relation between characters and the action of the story shifts historically, often miming shifts in society and its ideas about human individuality, self-determination, and the social order.',

            'meta_description'   => '',
            'meta_keywords'      => json_encode(['Fictional characters']),

        ]);

        $lNewVote->attachTag(SpatieTag::findOrCreate('Fairy', $this->votes_tag_type));
        TagDetail::create([
            'tag_id' => 20,
            'image'  => 'SophieAndersonTakethefairfaceofWoman.jpg',
            'description'=>'A fairy (also fata, fay, fey, fae, fair folk; from faery, faerie, "realm of the fays") is a type of mythical being or legendary creature in European folklore (and particularly Celtic, Slavic, German, English, and French folklore), a form of spirit, often described as metaphysical, supernatural, or preternatural.

Myths and stories about fairies do not have a single origin, but are rather a collection of folk beliefs from disparate sources. Various folk theories about the origins of fairies include casting them as either demoted angels or demons in a Christian tradition, as minor deities in pre-Christian Pagan belief systems, as spirits of the dead, as prehistoric precursors to humans, or as elementals.

The label of fairy has at times applied only to specific magical creatures with human appearance, small stature, magical powers, and a penchant for trickery. At other times it has been used to describe any magical creature, such as goblins and gnomes. Fairy has at times been used as an adjective, with a meaning equivalent to "enchanted" or "magical".

A recurring motif of legends about fairies is the need to ward off fairies using protective charms. Common examples of such charms include church bells, wearing clothing inside out, four-leaf clover, and food. Fairies were also sometimes thought to haunt specific locations, and to lead travelers astray using will-o\'-the-wisps. Before the advent of modern medicine, fairies were often blamed for sickness, particularly tuberculosis and birth deformities.

In addition to their folkloric origins, fairies were a common feature of Renaissance literature and Romantic art, and were especially popular in the United Kingdom during the Victorian and Edwardian eras. The Celtic Revival also saw fairies established as a canonical part of Celtic cultural heritage.',

            'meta_description'   => '',
            'meta_keywords'      => json_encode(['Fairy']),

        ]);


        $lNewVote= Vote::create([
            'id'               => 11,
            'name'             => 'Which planet is the closest to Earth ?',
            'slug'             => 'which-planet-is-the-closest-to-earth',
            'description'      => 'Which planet is the closest to Earth lorem  ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt  mollit anim id est laborum.
 lorem  ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt  mollit anim id est laborum. ',
            'creator_id'       => 1,
            'vote_category_id' => 3,  // Earth&World
            'is_quiz'          => true,
            'is_homepage'      => true,
            'ordering'         => 11,
            'status'           => 'A',
            'meta_description' => '',
            'meta_keywords'    => ['Earth', 'planets', 'Solar System'],
            'image'            => 'earth.jpg',
        ]);
        $lNewVote->attachTag(SpatieTag::findOrCreate('Solar System', $this->votes_tag_type));
        TagDetail::create([
            'tag_id' => 21,
            'image'  => 'Planets.png',
            'description'=>'The Solar System[a] is the gravitationally bound system of the Sun and the objects that orbit it, either directly or indirectly,[b] including the eight planets and five dwarf planets as defined by the International Astronomical Union (IAU). Of the objects that orbit the Sun directly, the largest eight are the planets,[c] with the remainder being smaller objects, such as dwarf planets and small Solar System bodies. Of the objects that orbit the Sun indirectly—the moons—two are larger than the smallest planet, Mercury.[d]

The Solar System formed 4.6 billion years ago from the gravitational collapse of a giant interstellar molecular cloud. The vast majority of the system\'s mass is in the Sun, with the majority of the remaining mass contained in Jupiter. The four smaller inner planets, Mercury, Venus, Earth and Mars, are terrestrial planets, being primarily composed of rock and metal. The four outer planets are giant planets, being substantially more massive than the terrestrials. The two largest, Jupiter and Saturn, are gas giants, being composed mainly of hydrogen and helium; the two outermost planets, Uranus and Neptune, are ice giants, being composed mostly of substances with relatively high melting points compared with hydrogen and helium, called volatiles, such as water, ammonia and methane. All eight planets have almost circular orbits that lie within a nearly flat disc called the ecliptic.

The Solar System also contains smaller objects.[e] The asteroid belt, which lies between the orbits of Mars and Jupiter, mostly contains objects composed, like the terrestrial planets, of rock and metal. Beyond Neptune\'s orbit lie the Kuiper belt and scattered disc, which are populations of trans-Neptunian objects composed mostly of ices, and beyond them a newly discovered population of sednoids. Within these populations are several dozen to possibly tens of thousands of objects large enough that they have been rounded by their own gravity. Such objects are categorized as dwarf planets. Identified dwarf planets include the asteroid Ceres and the trans-Neptunian objects Pluto and Eris.[e] In addition to these two regions, various other small-body populations, including comets, centaurs and interplanetary dust clouds, freely travel between regions. Six of the planets, at least four of the dwarf planets, and many of the smaller bodies are orbited by natural satellites,[f] usually termed "moons" after the Moon. Each of the outer planets is encircled by planetary rings of dust and other small objects.

The solar wind, a stream of charged particles flowing outwards from the Sun, creates a bubble-like region in the interstellar medium known as the heliosphere. The heliopause is the point at which pressure from the solar wind is equal to the opposing pressure of the interstellar medium; it extends out to the edge of the scattered disc. The Oort cloud, which is thought to be the source for long-period comets, may also exist at a distance roughly a thousand times further than the heliosphere. The Solar System is located in the Orion Arm, 26,000 light-years from the center of the Milky Way.',

            'meta_description'   => '',
            'meta_keywords'      => json_encode(['Solar System']),

        ]);

        $lNewVote->attachTag(SpatieTag::findOrCreate('Earth', $this->votes_tag_type));
        TagDetail::create([
            'tag_id' => 22,
            'image'  => 'SouthAmerica.jpg',
            'description'=>'Earth is the third planet from the Sun and the only astronomical object known to harbor life. According to radiometric dating and other sources of evidence, Earth formed over 4.5 billion years ago. Earth\'s gravity interacts with other objects in space, especially the Sun and the Moon, Earth\'s only natural satellite. Earth revolves around the Sun in 365.26 days, a period known as an Earth year. During this time, Earth rotates about its axis about 366.26 times.[n 5]

Earth\'s axis of rotation is tilted with respect to its orbital plane, producing seasons on Earth. The gravitational interaction between Earth and the Moon causes ocean tides, stabilizes Earth\'s orientation on its axis, and gradually slows its rotation. Earth is the densest planet in the Solar System and the largest of the four terrestrial planets.

Earth\'s lithosphere is divided into several rigid tectonic plates that migrate across the surface over periods of many millions of years. About 71% of Earth\'s surface is covered with water, mostly by oceans. The remaining 29% is land consisting of continents and islands that together have many lakes, rivers and other sources of water that contribute to the hydrosphere. The majority of Earth\'s polar regions are covered in ice, including the Antarctic ice sheet and the sea ice of the Arctic ice pack. Earth\'s interior remains active with a solid iron inner core, a liquid outer core that generates the Earth\'s magnetic field, and a convecting mantle that drives plate tectonics.

Within the first billion years of Earth\'s history, life appeared in the oceans and began to affect the Earth\'s atmosphere and surface, leading to the proliferation of aerobic and anaerobic organisms. Some geological evidence indicates that life may have arisen as much as 4.1 billion years ago. Since then, the combination of Earth\'s distance from the Sun, physical properties, and geological history have allowed life to evolve and thrive. In the history of the Earth, biodiversity has gone through long periods of expansion, occasionally punctuated by mass extinction events. Over 99% of all species that ever lived on Earth are extinct. Estimates of the number of species on Earth today vary widely; most species have not been described. Over 7.6 billion humans live on Earth and depend on its biosphere and natural resources for their survival. Humans have developed diverse societies and cultures; politically, the world has about 200 sovereign states.',

            'meta_description'   => 'Earth is the third planet from the Sun and the only astronomical object known to harbor life',
            'meta_keywords'      => json_encode(['Earth']),

        ]);


        $lNewVote= Vote::create([
            'id'               => 12,
            'name'             => 'According to the old proverb, to which European capital city do all roads lead ?',
            'slug'             => 'according-to-the-old-proverb-to-which-european-capital-city-do-all-roads-lead',
            'description'      => 'According to the old proverb, to which European capital city do all roads lead lorem  ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt  mollit anim id est laborum.',
            'creator_id'       => 1,
            'vote_category_id' => 4,   // History
            'is_quiz'          => true,
            'is_homepage'      => true,
            'ordering'         => 12,
            'status'           => 'A',
            'meta_description' => '',
            'meta_keywords'    => ['proverb', 'Ancient History', 'roads'],
            'image'            => 'ancient-roads.jpeg',
        ]);
        $lNewVote->attachTag(SpatieTag::findOrCreate('Ancient History', $this->votes_tag_type));


        $lNewVote= Vote::create([
            'id'               => 13,
            'name'             => 'On which mountain did Moses receive the Ten Commandments ?',
            'slug'             => 'on-which-mountain-did-moses-receive-the-ten-commandments',
            'description'      => 'On which mountain did Moses receive the Ten Commandments lorem  ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt  mollit anim id est laborum. 
Lorem  ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt  mollit anim id est laborum.           
 Lorem  ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt  mollit anim id est laborum. ',
            'creator_id'       => 1,
            'vote_category_id' => 4,   // History
            'is_quiz'          => true,
            'is_homepage'      => true,
            'ordering'         => 13,
            'status'           => 'A',
            'meta_description' => '',
            'meta_keywords'    => ['Ten Commandments', 'Mythology', 'Moses'],
            'image'            => 'ten-commandments.jpg',
        ]);
        $lNewVote->attachTag(SpatieTag::findOrCreate('Mythology', $this->votes_tag_type));
        TagDetail::create([
            'tag_id' => 23,
            'image'  => 'mythology.png',
            'description'=>'Myth is a folklore genre consisting of narratives that play a fundamental role in society, such as foundational tales. The main characters in myths are usually gods, demigods or supernatural humans. Myths are often endorsed by rulers and priests and are closely linked to religion or spirituality. In fact, many societies group their myths, legends and history together, considering myths to be true accounts of their remote past. Creation myths particularly, take place in a primordial age when the world had not achieved its later form. Other myths explain how a society\'s customs, institutions and taboos were established and sanctified. There is a complex relationship between recital of myths and enactment of rituals.

The study of myth began in ancient history. Rival classes of the Greek myths by Euhemerus, Plato and Sallustius were developed by the Neoplatonists and later revived by Renaissance mythographers. Today, the study of myth continues in a wide variety of academic fields, including folklore studies, philology, and psychology. The term mythology may either refer to the study of myths in general, or a body of myths regarding a particular subject. The academic comparisons of bodies of myth is known as comparative mythology.',

            'meta_description'   => 'Myth is a folklore genre consisting of narratives that play a fundamental role in society, such as foundational tales',
            'meta_keywords'      => json_encode(['Mythology']),

        ]);

        $lNewVote->attachTag(SpatieTag::findOrCreate('Religion', $this->votes_tag_type));
        TagDetail::create([
            'tag_id' => 24,
            'image'  => 'Open_Torah_and_pointer.jpg',
            'description'=>'Religion may be defined as a cultural system of designated behaviors and practices, worldviews, texts, sanctified places, prophecies, ethics, or organizations, that relates humanity to supernatural, transcendental, or spiritual elements. However, there is no scholarly consensus over what precisely constitutes a religion.

Different religions may or may not contain various elements ranging from the divine, sacred things, faith, a supernatural being or supernatural beings or "some sort of ultimacy and transcendence that will provide norms and power for the rest of life". Religious practices may include rituals, sermons, commemoration or veneration (of deities), sacrifices, festivals, feasts, trances, initiations, funerary services, matrimonial services, meditation, prayer, music, art, dance, public service, or other aspects of human culture. Religions have sacred histories and narratives, which may be preserved in sacred scriptures, and symbols and holy places, that aim mostly to give a meaning to life. Religions may contain symbolic stories, which are sometimes said by followers to be true, that have the side purpose of explaining the origin of life, the universe, and other things. Traditionally, faith, in addition to reason, has been considered a source of religious beliefs.

There are an estimated 10,000 distinct religions worldwide, but about 84% of the world\'s population is affiliated with one of the five largest religion groups, namely Christianity, Islam, Hinduism, Buddhism or forms of folk religion. The religiously unaffiliated demographic includes those who do not identify with any particular religion, atheists and agnostics. While the religiously unaffiliated have grown globally, many of the religiously unaffiliated still have various religious beliefs.

The study of religion encompasses a wide variety of academic disciplines, including theology, comparative religion and social scientific studies. Theories of religion offer various explanations for the origins and workings of religion, including the ontological foundations of religious being and belief.',

            'meta_description'   => 'Religion may be defined as a cultural system of designated behaviors and practices',
            'meta_keywords'      => json_encode(['Religion']),

        ]);


        $lNewVote= Vote::create([
            'id'               => 14,
            'name'             => 'Which is the tallest mammal?',
            'slug'             => 'which-is-the-tallest-mammal',
            'description'      => 'Which is the tallest mammal lorem  ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt  mollit anim id est laborum.',
            'creator_id'       => 1,
            'vote_category_id' => 3, // Earth&World
            'is_quiz'          => true,
            'is_homepage'      => true,
            'ordering'         => 14,
            'status'           => 'A',
            'meta_description' => '',
            'meta_keywords'    => ['World', 'Animals', 'mammal'],
            'image'            => 'mammals.jpg',
        ]);
        $lNewVote->attachTag(SpatieTag::findOrCreate('World', $this->votes_tag_type));
        $lNewVote->attachTag(SpatieTag::findOrCreate('Animals', $this->votes_tag_type));
        TagDetail::create([
            'tag_id' => 25,
            'image'  =>'Animal_diversity.png',
            'description'=>'Animals are multicellular eukaryotic organisms that form the biological kingdom Animalia. With few exceptions, animals consume organic material, breathe oxygen, are able to move, reproduce sexually, and grow from a hollow sphere of cells, the blastula, during embryonic development. Over 1.5 million living animal species have been described—of which around 1 million are insects—but it has been estimated there are over 7 million animal species in total. Animals range in length from 8.5 millionths of a metre to 33.6 metres (110 ft) and have complex interactions with each other and their environments, forming intricate food webs. The study of animals is called zoology.

Most living animal species are in the Bilateria, a clade whose members have a bilaterally symmetric body plan. The Bilateria include the protostomes—in which many groups of invertebrates are found, such as nematodes, arthropods, and molluscs—and the deuterostomes, containing the echinoderms and chordates (including the vertebrates). Life forms interpreted as early animals were present in the Ediacaran biota of the late Precambrian. Many modern animal phyla became clearly established in the fossil record as marine species during the Cambrian explosion which began around 542 million years ago. 6,331 groups of genes common to all living animals have been identified; these may have arisen from a single common ancestor that lived 650 million years ago.

Aristotle divided animals into those with blood and those without. Carl Linnaeus created the first hierarchical biological classification for animals in 1758 with his Systema Naturae, which Jean-Baptiste Lamarck expanded into 14 phyla by 1809. In 1874, Ernst Haeckel divided the animal kingdom into the multicellular Metazoa (now synonymous with Animalia) and the Protozoa, single-celled organisms no longer considered animals. In modern times, the biological classification of animals relies on advanced techniques, such as molecular phylogenetics, which are effective at demonstrating the evolutionary relationships between animal taxa.

Humans make use of many other animal species for food, including meat, milk, and eggs; for materials, such as leather and wool; as pets; and as working animals for power and transport. Dogs have been used in hunting, while many terrestrial and aquatic animals are hunted for sport. Non-human animals have appeared in art from the earliest times and are featured in mythology and religion.',

            'meta_description'   => 'Animals are multicellular eukaryotic organisms that form the biological kingdom Animalia',
            'meta_keywords'      => json_encode(['Animals', 'World']),

        ]);



        $lNewVote= Vote::create([
            'id'               => 15,
            'name'             => 'Who directed the movie Jaws?',
            'slug'             => 'who-directed-the-movie-jaws',
            'description'      => 'Who directed the movie Jaws lorem  ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt  mollit anim id est laborum. 
            lorem  ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt  mollit anim id est laborum.
lorem  ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt  mollit anim id est laborum. ',
            'creator_id'       => 1,
            'vote_category_id' => 2, // Movie&Cartoons
            'is_quiz'          => true,
            'is_homepage'      => true,
            'ordering'         => 15,
            'status'           => 'A',
            'meta_description' => '',
            'meta_keywords'    => ['Thriller', 'Jaws', 'Hollywood'],
            'image'            => 'movie-jaws.jpg',
        ]);

        $lNewVote->attachTag(SpatieTag::findOrCreate('Thriller', $this->votes_tag_type));
        TagDetail::create([
            'tag_id' => 26,
            'image'  => 'RebeccaTrailer.jpg',
            'description'=>'Thriller film, also known as suspense film or suspense thriller, is a broad film genre that involves excitement and suspense in the audience. The suspense element, found in most films\' plots, is particularly exploited by the filmmaker in this genre. Tension is created by delaying what the audience sees as inevitable, and is built through situations that are menacing or where escape seems impossible.

The cover-up of important information from the viewer, and fight and chase scenes are common methods. Life is typically threatened in thriller film, such as when the protagonist does not realize that they are entering a dangerous situation. Thriller films\' characters conflict with each other or with an outside force, which can sometimes be abstract. The protagonist is usually set against a problem, such as an escape, a mission, or a mystery.

Thriller films are typically hybridized with other genres; hybrids commonly including: action thrillers, adventure thrillers, fantasy and science fiction thrillers. Thriller films also share a close relationship with horror films, both eliciting tension. In plots about crime, thriller films focus less on the criminal or the detective and more on generating suspense. Common themes include, terrorism, political conspiracy, pursuit and romantic triangles leading to murder.

In 2001, the American Film Institute made its selection of the top 100 greatest American "heart-pounding" and "adrenaline-inducing" films of all time. The 400 nominated films had to be American-made films whose thrills have "enlivened and enriched America\'s film heritage". AFI also asked jurors to consider "the total adrenaline-inducing impact of a film\'s artistry and craft".',

            'meta_description'   => 'Thriller film, also known as suspense film or suspense thriller',
            'meta_keywords'      => json_encode(['Thriller']),

        ]);

        $lNewVote->attachTag(SpatieTag::findOrCreate('Hollywood', $this->votes_tag_type));
        $lNewVote->attachTag(SpatieTag::findOrCreate('Movie director', $this->votes_tag_type));




        $lNewVote= Vote::create([
            'id'               => 16,
            'name'             => 'In the film Babe, what type of animal was Babe?',
            'slug'             => 'in-the-film-babe-what-type-of-animal-was-babe',
            'description'      => 'Babe is a 1995 Australian-American comedy-drama film directed by <i>Chris Noonan</i>, produced by <i>George Miller</i>, and written by both. It is an adaptation of Dick King-Smith\'s 1983 novel The Sheep-Pig, also known as Babe: The Gallant Pig in the US, which tells the story of a pig raised as livestock who wants to do the work of a sheepdog. The main animal characters are played by a combination of real and animatronic pigs and Border Collies.[3]

After seven years of development, Babe was filmed in Robertson, New South Wales, Australia. The talking-animal visual effects were done by Rhythm & Hues Studios and Jim Henson\'s Creature Shop. The film was both a box office and critical success, and in 1998 Miller went on to direct a sequel, Babe: Pig in the City.

Babe, an orphaned piglet, is chosen for a "guess the weight" contest at a county fair. The winning farmer, Arthur Hoggett, brings him home and allows him to stay with a Border Collie named Fly, her mate Rex and their puppies, in the barn.

A duck named Ferdinand, who crows as roosters are said to every morning to wake people so he will be considered useful and be spared from being eaten, persuades Babe to help him destroy the alarm clock that threatens his mission. Despite succeeding in this, they wake Duchess, the Hoggetts\' cat, and in the confusion accidentally destroy the living room. Rex sternly instructs Babe to stay away from Ferdinand (now a fugitive) and the house. Sometime later, when Fly\'s puppies are put up for sale, Babe asks if he can call her "Mom".

Christmas brings a visit from the Hoggetts\' relatives. Babe is almost chosen for Christmas dinner but a duck is picked instead after Hoggett remarks to his wife Esme that Babe may bring a prize for ham at the next county fair. On Christmas Day, Babe justifies his existence by alerting Hoggett to sheep rustlers stealing sheep from one of the fields. The next day, Hoggett sees Babe sort the hens, separating the brown from the white ones. Impressed, he takes him to the fields and allows him to try and herd the sheep. Encouraged by an elder ewe named Maa, the sheep cooperate, but Rex sees Babe\'s actions as an insult to sheepdogs and confronts Fly in a vicious fight for encouraging Babe. He injures her leg and accidentally bites Hoggett\'s hand when he tries to intervene. Rex is then chained to the dog house, muzzled and sedated, leaving the sheep herding job to Babe.',
            'creator_id'       => 1,
            'vote_category_id' => 2, // Movie&Cartoons
            'is_quiz'          => true,
            'is_homepage'      => true,
            'ordering'         => 16,
            'status'           => 'A',
            'meta_description' => '',
            'meta_keywords'    => ['Drama', 'Animals', 'Babe'],
            'image'            => 'Babe_ver1.jpg',
        ]);
        $lNewVote->attachTag(SpatieTag::findOrCreate('Drama', $this->votes_tag_type));
        $lNewVote->attachTag(SpatieTag::findOrCreate('Animals', $this->votes_tag_type));





//        Mount Everest is found in which mountain range  The Himalayas.
        $lNewVote= Vote::create([
    'id'               => 17,
    'name'             => 'Mount Everest is found in which mountain range?',
    'slug'             => 'mount-everest-is-found-in-which-mountain-range',
    'description'      => 'Mount Everest, known in Nepali as Sagarmatha (सगरमाथा) and in Tibetan as Chomolungma (ཇོ་མོ་གླང་མ), is Earth\'s highest mountain above sea level, located in the Mahalangur Himal sub-range of the Himalayas. The international border between Nepal (Province No. 1) and China (Tibet Autonomous Region) runs across its summit point.

The current official elevation of 8,848 m (29,029 ft), recognized by China and Nepal, was established by a 1955 Indian survey and subsequently confirmed by a Chinese survey in 1975. In 2005, China remeasured the rock height of the mountain, with a result of 8844.43 m. There followed an argument between China and Nepal as to whether the official height should be the rock height (8,844 m., China) or the snow height (8,848 m., Nepal). In 2010, an agreement was reached by both sides that the height of Everest is 8,848 m, and Nepal recognizes China\'s claim that the rock height of Everest is 8,844 m.

In 1865, Everest was given its official English name by the Royal Geographical Society, upon a recommendation by Andrew Waugh, the British Surveyor General of India. As there appeared to be several different local names, Waugh chose to name the mountain after his predecessor in the post, Sir George Everest, despite George Everest\'s objections.

Mount Everest attracts many climbers, some of them highly experienced mountaineers. There are two main climbing routes, one approaching the summit from the southeast in Nepal (known as the "standard route") and the other from the north in Tibet. While not posing substantial technical climbing challenges on the standard route, Everest presents dangers such as altitude sickness, weather, and wind, as well as significant hazards from avalanches and the Khumbu Icefall. As of 2017, nearly 300 people have died on Everest, many of whose bodies remain on the mountain.

The first recorded efforts to reach Everest\'s summit were made by British mountaineers. As Nepal did not allow foreigners into the country at the time, the British made several attempts on the north ridge route from the Tibetan side. After the first reconnaissance expedition by the British in 1921 reached 7,000 m (22,970 ft) on the North Col, the 1922 expedition pushed the north ridge route up to 8,320 m (27,300 ft), marking the first time a human had climbed above 8,000 m (26,247 ft). Seven porters were killed in an avalanche on the descent from the North Col. The 1924 expedition resulted in one of the greatest mysteries on Everest to this day: George Mallory and Andrew Irvine made a final summit attempt on 8 June but never returned, sparking debate as to whether or not they were the first to reach the top. They had been spotted high on the mountain that day but disappeared in the clouds, never to be seen again, until Mallory\'s body was found in 1999 at 8,155 m (26,755 ft) on the north face. Tenzing Norgay and Edmund Hillary made the first official ascent of Everest in 1953, using the southeast ridge route. Norgay had reached 8,595 m (28,199 ft) the previous year as a member of the 1952 Swiss expedition. The Chinese mountaineering team of Wang Fuzhou, Gonpo, and Qu Yinhua made the first reported ascent of the peak from the north ridge on 25 May 1960.',
    'creator_id'       => 1,
    'vote_category_id' => 3, // Earth&World
    'is_quiz'          => true,
    'is_homepage'      => true,
    'ordering'         => 17,
    'status'           => 'A',
    'meta_description' => '',
    'meta_keywords'    => ['Everest', 'Interesting Places', 'World'],
    'image'            => 'Everest.jpeg',
]);
        $lNewVote->attachTag(SpatieTag::findOrCreate('Interesting Places', $this->votes_tag_type));
        $lNewVote->attachTag(SpatieTag::findOrCreate('World', $this->votes_tag_type));



        /*       18 . In Greek mythology, who turned all that he touched into gold?  A. Midas. */
        $lNewVote= Vote::create([
            'id'               => 18,
            'name'             => 'In Greek mythology, who turned all that he touched into gold?',
            'slug'             => 'in-greek-mythology-who-turned-all-that-he-touched-into-gold',
            'description'      => 'Greek mythology is the body of myths originally told by the ancient Greeks. These stories concern the origin and the nature of the world, the lives and activities of deities, heroes, and mythological creatures, and the origins and significance of the ancient Greeks\' own cult and ritual practices. Modern scholars study the myths in an attempt to shed light on the religious and political institutions of ancient Greece and its civilization, and to gain understanding of the nature of myth-making itself.

The Greek myths were initially propagated in an oral-poetic tradition most likely by Minoan and Mycenaean singers starting in the 18th century BC; eventually the myths of the heroes of the Trojan War and its aftermath became part of the oral tradition of Homer\'s epic poems, the Iliad and the Odyssey. Two poems by Homer\'s near contemporary Hesiod, the Theogony and the Works and Days, contain accounts of the genesis of the world, the succession of divine rulers, the succession of human ages, the origin of human woes, and the origin of sacrificial practices. Myths are also preserved in the Homeric Hymns, in fragments of epic poems of the Epic Cycle, in lyric poems, in the works of the tragedians and comedians of the fifth century BC, in writings of scholars and poets of the Hellenistic Age, and in texts from the time of the Roman Empire by writers such as Plutarch and Pausanias.

Aside from this narrative deposit in ancient Greek literature, pictorial representations of gods, heroes, and mythic episodes featured prominently in ancient vase-paintings and the decoration of votive gifts and many other artifacts. Geometric designs on pottery of the eighth century BC depict scenes from the Trojan cycle as well as the adventures of Heracles. In the succeeding Archaic, Classical, and Hellenistic periods, Homeric and various other mythological scenes appear, supplementing the existing literary evidence.

Greek mythology has had an extensive influence on the culture, arts, and literature of Western civilization and remains part of Western heritage and language. Poets and artists from ancient times to the present have derived inspiration from Greek mythology and have discovered contemporary significance and relevance in the themes.',
            'creator_id'       => 1,
            'vote_category_id' => 3, // Earth&World
            'is_quiz'          => true,
            'is_homepage'      => true,
            'ordering'         => 18,
            'status'           => 'A',
            'meta_description' => '',
            'meta_keywords'    => ['Mythology', 'Achilles Penthesileia', 'Greek mythology'],
            'image'            => 'Achilles_Penthesileia.jpg',
        ]);
        $lNewVote->attachTag(SpatieTag::findOrCreate('Mythology', $this->votes_tag_type));


        /* . 19 The title role of the 1990 movie “Pretty Woman” was played by which actress? A. Julia Roberts.  */
        $lNewVote= Vote::create([
            'id'               => 19,
            'name'             => 'The title role of the 1990 movie “Pretty Woman” was played by which actress?',
            'slug'             => 'the-title-role-of-the-1990-movie-pretty-woman-was-played-by-which-actress',
            'description'      => 'Pretty Woman is a 1990 American romantic comedy film directed by Garry Marshall from a screenplay by J. F. Lawton. The film stars <i>Richard Gere</i> and Julia Roberts, and features Hector Elizondo, Ralph Bellamy (in his final performance), Laura San Giacomo, and Jason Alexander in supporting roles. The film\'s story centers on down-on-her-luck <b>Hollywood</b> prostitute Vivian Ward, who is hired by Edward Lewis, a wealthy businessman, to be his escort for several business and social functions, and their developing relationship over the course of her week-long stay with him.

Originally intended to be a dark cautionary tale about class and sex work in Los Angeles, the film was reconceived as a romantic comedy with a large budget. It was widely successful at the box office and was the third highest-grossing film of 1990. The film saw the highest number of ticket sales in the U.S. ever for a romantic comedy, with Box Office Mojo listing it as the #1 romantic comedy by the highest estimated domestic tickets sold at 42,176,400, slightly ahead of My Big Fat Greek Wedding (2002) at 41,419,500 tickets. The film received positive reviews, with Roberts\'s performance being praised, for which she received a Golden Globe Award and a nomination for the Academy Award for Best Actress. In addition, screenwriter J. F. Lawton was nominated for a Writers Guild Award and a BAFTA Award.',
            'creator_id'       => 1,
            'vote_category_id' => 2, // Movie&Cartoons
            'is_quiz'          => true,
            'is_homepage'      => false,
            'ordering'         => 19,
            'status'           => 'N',
            'meta_description' => '',
            'meta_keywords'    => ['Pretty woman', 'Hollywood'],
            'image'            => 'Pretty_woman_movie.jpg',
        ]);
        $lNewVote->attachTag(SpatieTag::findOrCreate('Film', $this->votes_tag_type));
        $lNewVote->attachTag(SpatieTag::findOrCreate('Hollywood', $this->votes_tag_type));

    }
}
