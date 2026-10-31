<?php

namespace Database\Seeders;

use App\Models\ContentBlock;
use App\Models\Feature;
use App\Models\Partner;
use App\Models\SuccessVideo;
use App\Models\TeamMember;
use App\Models\Testimonial;
use Illuminate\Database\Seeder;

/**
 * Starting content for the home page sections (taken from europeanit-inst.com).
 * After this, everything is edited from the dashboard.
 * Run: php artisan db:seed --class=HomeContentSeeder
 */
class HomeContentSeeder extends Seeder
{
    public function run(): void
    {
        $this->features();
        $this->contentBlocks();
        $this->successVideos();
        $this->testimonials();
        $this->team();
        $this->partners();
    }

    // "কেন ইউরোপিয়ান আইটিকে বেছে নেবেন?"
    private function features(): void
    {
        $features = [
            ['২৪/৭ অনলাইন সহায়তা — আপনার শেখার যাত্রায় নিরবিচ্ছিন্ন সঙ্গী', 'support.webp', '#EAF6F0',
                '<p>শেখা শুরু হতে পারে যেকোনো সময়ে, আর সমস্যাও আসতে পারে ঠিক তখনই। তাই European IT™ সর্বদা প্রস্তুত—২৪ ঘণ্টা, সপ্তাহে ৭ দিন অনলাইন সহায়তা দিয়ে শিক্ষার্থীদের পাশে থাকার জন্য।</p>'
                .'<p>ক্লাস বুঝতে সমস্যা? ভিডিও বা লার্নিং ম্যাটেরিয়াল পাচ্ছেন না? প্রযুক্তিগত ঝামেলা? আমাদের সাপোর্ট টিম সবসময় দ্রুত ও কার্যকর সমাধান দিতে প্রস্তুত!</p>'
                .'<p>আপনার শেখার অভিজ্ঞতা যাতে হয় নিরবিচ্ছিন্ন, মসৃণ ও আত্মবিশ্বাসে পরিপূর্ণ—আমরা আছি, সবসময় আপনার পাশে।</p>'],
            ['লাইফটাইম সাপোর্ট — শেখা শেষ নয়, এটি কেবল শুরু', 'lifetime-support.webp', '#F0F8E2',
                '<p>European IT™-এর প্রতিশ্রুতি কোর্স শেষের গণ্ডিতে সীমাবদ্ধ নয়। আমরা প্রদান করি আজীবন সহায়তা, যাতে আপনি স্কিল্ল হবার পরও বাস্তব জীবনের আইটি চ্যালেঞ্জগুলোতে পাশে পাবেন আমাদের অভিজ্ঞ মেন্টরদের।</p>'
                .'<p>হোক তা চাকরির কোনো জটিল প্রজেক্ট, অথবা নতুন কোনো প্রযুক্তি শেখার প্রয়াস—আপনার পেশাগত সফলতার প্রতিটি ধাপে আমরা আছি পাশে।</p>'],
            ['প্র্যাকটিস ল্যাব সাপোর্ট — শেখার পরিধি আরও বিস্তৃত', 'practice-lab.webp', '#EBE7FA',
                '<p>দক্ষতা অর্জনের মূল চাবিকাঠি হলো নিয়মিত অনুশীলন। তাই European IT™-এ নির্ধারিত ক্লাসের বাইরে শিক্ষার্থীদের জন্য উন্মুক্ত রাখা হয়েছে আমাদের সুসজ্জিত প্র্যাকটিস ল্যাব।</p>'
                .'<p>এখানে শিক্ষার্থীরা:</p><ul><li>অ্যাসাইনমেন্ট অনুশীলন করতে পারে,</li><li>নিজস্ব প্রজেক্টে কাজ করতে পারে,</li><li>সহপাঠীদের সঙ্গে মিলেমিশে শেখার অভিজ্ঞতা শেয়ার করতে পারে।</li></ul>'
                .'<p>এই হাতে-কলমে শেখার পরিবেশ শিক্ষার্থীদের দক্ষতা গঠনে সহায়ক ভূমিকা রাখে এবং বাস্তব জীবনে আত্মবিশ্বাস তৈরি করে।</p>'],
            ['ক্লাস ভিডিও — শেখা হবে আপনার সময় অনুযায়ী', 'class-video.webp', '#EAF6F0',
                '<p>ক্লাস মিস করেছেন? চিন্তার কিছু নেই। European IT™-এ প্রতিটি ক্লাস সেশন রেকর্ড করা হয় এবং শিক্ষার্থীদের জন্য সহজে অ্যাক্সেসযোগ্য ভিডিও আকারে শেয়ার করা হয়।</p>'
                .'<p>এই মানসম্পন্ন ভিডিও লেসনগুলো আপনাকে:</p><ul><li>যে কোনো সময় ক্লাস রিভিউ করার সুযোগ দেয়,</li><li>কঠিন কনসেপ্টগুলো বারবার দেখে বুঝতে সহায়তা করে,</li><li>নিজের গতিতে শেখার স্বাধীনতা নিশ্চিত করে।</li></ul>'
                .'<p>পুনরাবৃত্তি, আত্মবিশ্বাস ও দক্ষতা বৃদ্ধির জন্য এটি এক অনন্য সহায়ক উপকরণ।</p>'],
            ['রিভিউ ক্লাস — নিশ্চিত করুন আপনার শেখার ধারাবাহিকতা', 'review-class.webp', '#E8EAFA',
                '<p>যে কোনো শিক্ষার্থী যেন পিছিয়ে না পড়ে, সে লক্ষ্যে European IT™ নিয়মিত আয়োজন করে রিভিউ ক্লাস। এসব সেশনে পূর্বের পাঠগুলো পুনরায় আলোচনা করা হয়, ব্যক্তিগত সমস্যাগুলো সমাধান করা হয় এবং বিষয়গুলো আরও গভীরভাবে বোঝানো হয়।</p>'
                .'<p>এটি দক্ষতা বৃদ্ধির পাশাপাশি মেন্টরদের কাছ থেকে ব্যক্তিগত মতামত ও পরামর্শ নেওয়ার একটি মূল্যবান সুযোগ।</p>'],
            ['জব প্লেসমেন্ট — দক্ষতাকে ক্যারিয়ারে রূপান্তর', 'job-placement.webp', '#EAF6F0',
                '<p>আমরা বিশ্বাস করি, দক্ষতা অর্জন শুধু শিখেই শেষ নয়; সেটিকে সফল ক্যারিয়ারে রূপান্তর করাই শিক্ষার মূল লক্ষ্য। European IT™-এর বিশেষ জব প্লেসমেন্ট সেল শিক্ষার্থীদের জন্য করে বাস্তব চাকরির সুযোগের সেতুবন্ধন—আমাদের অভ্যন্তরীণ নেটওয়ার্ক এবং বিশ্বস্ত পার্টনার প্রতিষ্ঠানসমূহের মাধ্যমে।</p>'
                .'<p>সিভি প্রস্তুতি থেকে শুরু করে ইন্টারভিউ প্রশিক্ষণ পর্যন্ত, চাকরির প্রতিটি ধাপে আমরা আছি আপনার পাশে, নিশ্চিত করতে সফলতার পূর্ণ সহযোগিতা।</p>'],
        ];

        foreach ($features as $order => [$title, $icon, $color, $body]) {
            Feature::updateOrCreate(['title' => $title], [
                'body' => $body, 'icon' => "images/home/why/{$icon}", 'bg_color' => $color, 'sort_order' => $order,
            ]);
        }
    }

    // "Our Specialty" + "15+ Years of Trusted Service"
    private function contentBlocks(): void
    {
        $buttons = [
            ['label' => 'Our Courses', 'url' => '/european-it-courses'],
            ['label' => 'Learn More', 'url' => '/old-about-us'],
        ];

        ContentBlock::updateOrCreate(['key' => 'home.specialty'], [
            'eyebrow' => 'Our Specialty',
            'title'   => 'Live Project-Based Classes at European IT™',
            'image'   => 'images/home/specialty/project-based-classes.webp',
            'body'    => '<p>At <strong>European IT™</strong>, we believe that true mastery comes from practical experience. That’s why our courses are designed around <strong>real-world, project-based learning</strong>—where you don’t just learn concepts, you apply them.</p>'
                .'<p>Under the guidance of industry-expert instructors, you’ll build <strong>hands-on projects</strong> that reflect actual job requirements, helping you develop a robust portfolio. This practical approach not only deepens your understanding but also prepares you to confidently step into the job market with skills that truly stand out to employers.</p>',
        ]);

        ContentBlock::updateOrCreate(['key' => 'home.trusted'], [
            'eyebrow' => '15+ Years of Trusted Service',
            'title'   => 'Developing the Next Generation of IT Specialists',
            'image'   => 'images/home/specialty/trusted-service.webp',
            'buttons' => $buttons,
            'body'    => '<p>For over 15 years, <strong>European IT™</strong> has been at the forefront of cultivating skilled IT professionals. With a commitment to delivering high-quality education, we’ve empowered more than <strong>80,000 students</strong>—many of whom are now thriving in both local and global tech industries.</p>'
                .'<p>In today’s rapidly evolving digital landscape, our mission remains clear: to equip you with the <strong>cutting-edge skills and practical experience</strong> needed to stay ahead. At European IT, we don’t just train learners—we shape confident, job-ready professionals prepared to lead the future of technology.</p>',
        ]);
    }

    // "Success Stories" YouTube videos
    private function successVideos(): void
    {
        $videos = ['q1Q0hFDTlIg', 'iCZ4Szzc6Rg', 'REptAYuMElE', 'op_8wxwdWJ0', 'Dq_K-1RUpDc', 'ZxQKZ92mLU4'];

        foreach ($videos as $order => $id) {
            SuccessVideo::updateOrCreate(
                ['youtube_url' => "https://www.youtube.com/watch?v={$id}"],
                ['title' => 'Student success story '.($order + 1), 'sort_order' => $order],
            );
        }
    }

    // "Student's Feedback"
    private function testimonials(): void
    {
        $reviews = [
            ['Shafin Chowdhury', 'React js', 'EITReact221002', 'shafin-chowdhury.webp',
                "EUROPEAN IT INSTITUTE\nEuropean IT Institute is one of the best professional IT Training Institute in Bangladesh which was firstly a service company in Ireland. Later The founders made a great decision and decided to establish it in Bangladesh as well. European IT Institute is surrounded with many facilities and good environment also with numerous telented teachers and supporters .The Teachers are really supportive .Whenever we have any query they always give as 24/7 online or offline support. As a current student of European IT Institute from my point of view it is a great place to learn.\nBest wishes for European IT ❤️"],
            ['Moeen Islam', 'React js', 'EITReact221003', 'moeen-islam.webp',
                "If you're looking for a IT training institute in Bangladesh, look no further than the European IT Solutions and Training Institute . With a lot of experience, the institute offers a wide range of courses that will help you stay up-to-date on the latest technology trends. I learned JavaScript and React Development from this Institute and they helped me to learn this language in a very easy way. They provide a professional and Experienced mentor to teach us. I am very satisfied with their training course."],
            ['Lubna Begum', 'Web Development With WordPress', 'EITWDDWW221004', 'lubna-begum.webp',
                'যে কোনো কোর্সে ভর্তি হওয়ার ক্ষেত্রে প্রথমেই আমাদের যে বিষয়টি নিয়ে আতঙ্কে পড়তে হয় সেটি হলো মানসম্মত প্রতিষ্ঠান বেছে নেওয়া। আমাদের দেশে অনেক আইটি ট্রেনিং সেন্টার রয়েছে। কিন্তু এগুলোর মধ্যে থেকে মানসম্মত আইটি সেন্টার খুঁজে বের করা অনেকটা কষ্টকর ব্যাপার হয়ে দাঁড়ায়। আমি আমার ইন্ডাস্ট্রিয়াল অ্যাটাচমেন্ট এর জন্য বেশ কিছু আইটি সেন্টার যাচাই-বাছাই করার পর ইউরোপিয়ান আইটি সল্যুশনস ইন্সটিটিউট কে বেছে নেই। যার কিছু কারণ হচ্ছে- ১. সুন্দর ও মনোরম পরিবেশে ক্লাস ২. শিক্ষকদের আন্তরিকতা ৩. সার্টিফাইড প্রশিক্ষকদের তত্ত্বাবধানে পরিচালিত। ইউরোপিয়ান আইটির কোর্সে ভর্তি হয়ে অনেকগুলো সুযোগ সুবিধা পাই, যেগুলো আমাকে কাজ শেখার আগ্রহ অনেকটা বাড়িয়ে দেয়। আলহামদুলিল্লাহ এইখানে ক্লাস করে নিজেই নিজের কাজের ইম্প্রুভমেন্ট টা বুঝতে পারছি ।'],
            ['Klaudiya Bithi Murmu', 'Graphic Design', 'EITGD220903', 'klaudiya-bithi-murmu.webp',
                "European IT Solution Institute এই প্রতিষ্ঠানটি আমার কাছে শিখার জন্য মানসম্মত শিক্ষাপ্রতিষ্ঠান মনে হয়েছে। যেখানে প্রশিক্ষক তাদের সর্বোচ্চটা দিয়ে একজন প্রশিক্ষনার্থীকে বাস্তবে হাতে কলমে শেখায় এবং প্রশিক্ষকদের ব্যবহার খুবই বন্ধুসুলভ। এছাড়াও প্রতিষ্ঠানটি অনেক নিরিবিলি ও মনোরম পরিবেশে তাই কোনো রকম অসুবিধা হয় না। পুঁথিগত শিক্ষার চেয়ে হাতে কলমে শিক্ষার দাম বরাবরই বেশি।তাই কোনো কাজে সমস্যা হলে আমি আমার প্রশিক্ষকের কাছেও সাহায্য পেয়েছি।\nসর্বশেষে, এই প্রতিষ্ঠানটি বাস্তবমুখী প্রতিষ্ঠান হওয়ায় আমাকে দক্ষ হতে সাহায্য করছে।এই তিন মাসে অজানা অনেক কিছু জানতে পেরেছি। তাই আবারও অনেক অনেক ধন্যবাদ ও কৃতজ্ঞতা জানাই European IT Solution Institute -কে আমাকে সুযোগ দানের জন্য।\nইউরোপিয়ান আইটির জন্য অনেক অনেক শুভ কামনা ❤️"],
            ['Tawfiq Islam', 'Web Development With WordPress', 'EITWDDWW221003', 'tawfiq-islam.webp',
                'ইউরোপিয়ান আইটি একটি সুন্দর পরিবেশ, এবং অনেক সু - দক্ষ শিক্ষক দ্বারা পরিচালিত। ক্লাস সমূহ অনেক দক্ষতার সাথে আমাদের ওয়েব ডেভলপার শিক্ষক, মোঃ ফারুক আহমেদ স্যার নিয়ে থাকেন। আমি অত্যন্ত আনন্দিত, যে এই আইটি শিক্ষা নিতে পারছি। এবং আমি এর দ্বারা নিজের জন্যে অনেক ভালো একটা প্ল্যাটফর্ম করে নিতে পারবো। আমি আমার পরবর্তী সময়ের ছোট ভাই দের এইখানে পড়ার জন্যে উৎসাহিত করবো।'],
            ['Swapan Islam Robin', 'Graphic Design', 'EITGD220901', 'swapan-islam-robin.webp',
                "আমি স্বপন ইসলাম রবিন। নরসিংদী পলিটেকনিক ইনস্টিটিউট একজন ছাত্র। ছাত্র-ছাত্রী শিক্ষাজীবনে ইন্ডাস্ট্রিয়াল এটার্চমেন্ট ট্রেনিং অত্যন্ত গুরুত্বপূর্ণ। যা তার ভবিষ্যৎ জীবনের কার্যকর ভূমিকা পালন করে। যেন ভালো প্রতিষ্ঠানে ট্রেনিং করতে পারে। আর ইউরোপিয়ান আইটি ইন্সটিটিউট শিক্ষার্থীদের জন্য ভালো একটি প্রতিষ্ঠান। তারা অত্যন্ত যত্ন সহকারে শিক্ষার্থীরদের ট্রেনিং বিষয়গুলো তুলে ধরেন। এবং সবগুলো ট্রেনিং সঠিক ও সফলভাবে পূরণ করেন। আমি এখানে একজন ছাত্র হিসেবে কৃতজ্ঞতা জ্ঞাপন করছি। ইউরোপিয়ান আইটি ইনস্টিটিউট প্রশিক্ষণগণ আমাদের কাছে প্রকৃত বিষয়গুলো সঠিকভাবে উপস্থাপন করেছেন।\nএবং আমাদের খুঁটিনাটি ভুলগুলো শুদ্ধ করে দিয়েছেন।"],
        ];

        foreach ($reviews as $order => [$name, $course, $batch, $photo, $message]) {
            Testimonial::updateOrCreate(['name' => $name], [
                'course_name' => $course, 'batch' => $batch, 'message' => $message,
                'photo' => "images/testimonials/{$photo}", 'sort_order' => $order,
            ]);
        }
    }

    // "Our Support Team"
    private function team(): void
    {
        $team = [
            ['Faruk Aahmed', 'Sr. Web Developer', 'faruk-aahmed.webp', 'I’m a WordPress web developer with over 5 years of experience in creating dynamic, user-friendly websites. Proficient in PHP, HTML, CSS, and JavaScript, I specialize in custom theme development and plugin integration. Dedicated to optimizing site performance and enhancing user experience, I deliver tailored solutions that meet clients’ unique needs.'],
            ['Al-Nayeem Tarafder', 'HR & Administration Manager', 'al-nayeem-tarafder.webp', 'I am Al-Nayeem Tarafder, currently serving as the HR & Administration Manager at European IT Solutions Institute. With more than six years of professional experience in Human Resources and Administration. Prior to my current role, I worked in the HR department of Ashulia Women & Children Hospital and Foodpanda Bangladesh Limited. I completed my Master’s degree in Public Administration from Jahangirnagar University. My professional goal is to contribute strategically to organizational growth through effective human resource management, employee development, and administrative excellence.'],
            ['Al Kafi Sohag', 'Sr. Laravel Developer', 'al-kafi-sohag.webp', 'I’m a Laravel developer with over 5 years of experience in building robust, scalable web applications. Skilled in PHP, Laravel, and modern JavaScript frameworks, I focus on creating efficient and maintainable code. Committed to delivering high-quality solutions, I excel in both developing new systems and enhancing existing applications to meet client needs.'],
            ['Tajnin Ahad Prova', 'Service Executive', 'tajnin-ahad-prova.png', 'I am a Student Service Executive with a passion for student support, effective communication, and delivering exceptional customer service. I specialize in coordinating student activities, managing class schedules, maintaining accurate records, and providing career and job placement assistance. By collaborating with trainers and internal teams, I strive to create a positive learning environment that ensures excellent service and fosters long-term student engagement.'],
            ['Shormi Akter', 'Counselor & Business Communication Officer', 'shormi-akter.png', "I am Shormi Akter, currently serving as a Counselor & Business Communication Officer at European IT Solutions Institute.\n\nWith over 3 years of experience in brand communication and digital marketing, I specialize in client communication, content development, copywriting, social media management, and campaign planning. I have worked with multiple brands to create audience-focused content that enhances brand presence and engagement.\n\nPassionate about strategic communication and continuous learning, I keep expanding my expertise in digital marketing and SEO to deliver creative, result-driven solutions that support business growth."],
            ['MD ABU SAYED', 'Web Developer', 'md-abu-sayed.webp', 'I’m MD ABU SAYED, a Front-End Developer with 1+ year of experience in building responsive and user-friendly web applications using React.js, JavaScript, Tailwind CSS, Bootstrap, and WordPress. I focus on creating clean, modern, and high-performing digital experiences.'],
            ['MD.RAJIUR RAHMAN', 'Web Developer', 'md-rajiur-rahman.png', 'I’m MD. RAJIUR RAHMAN, a dedicated Front-End Developer skilled in creating responsive and modern web applications using React.js, JavaScript, Tailwind CSS, Bootstrap, and WordPress. I enjoy building clean, user-friendly, and performance-focused digital experiences.'],
            ['Rauful Islam', 'Sr. Graphics Designer', 'rauful-islam.webp', 'I’m a graphic designer with over 5 years of experience in creating visually compelling designs that communicate brand messages effectively. Proficient in Adobe Creative Suite and various design tools, I specialize in branding, digital media, and print design. Dedicated to staying current with design trends, I aim to deliver innovative solutions that captivate and engage audiences.'],
            ['Azizul Islam', 'Sr. Graphics Designer', 'azizul-islam.webp', 'A creative graphic designer with a keen eye for aesthetics and a passion for visual storytelling. Skilled in branding, typography, and digital design, they bring ideas to life through innovative graphics, illustrations, and layouts. Proficient in industry-standard tools like Adobe Creative Suite, they craft compelling visuals that engage audiences and enhance brand identity.'],
            ['Samia Islam', 'Communication Officer', 'samia-islam.webp', 'I am a Communication Officer with 1+ years of experience, specializing in creating impactful, audience-centered messaging that enhances engagement and brand presence. With expertise in strategic communication, digital platforms, and content development, I design and execute responsive, SEO-driven campaigns that support organizational goals and reach targeted audiences. I’m dedicated to crafting scalable communication strategies that transform ideas into effective, memorable experiences.'],
            ['Md Toabur Rahman', 'Sr. Web Developer', 'md-toabur-rahman.webp', 'NSDA Certified Mentor and WordPress Developer with 5+ years of experience in web development and IT training. Proficient in WordPress, PHP, JavaScript, and modern web technologies. Passionate about building high-quality, user-friendly websites and mentoring future IT professionals with practical, industry-focused skills.'],
            ['Md. Abu Bakar', 'Digital Marketing Executive & Mentor', 'md-abu-bakar.png', 'Certified Digital Marketing Expert with 5+ years of experience and professional certifications from NSDA, HubSpot, and Google Skillshop. I help businesses increase their online visibility, generate qualified leads, and boost revenue through SEO, Social Media Marketing, Google Ads, Meta Ads, and data-driven marketing strategies. Having worked with clients worldwide through Upwork and Fiverr. I focus on delivering measurable results and sustainable business growth.'],
            ['Fahim Elahi', 'Mentor & Industrial Expert, Cyber Security & Ethical Hacking Department', 'fahim-elahi.webp', 'Fahim Elahi is a cybersecurity professional based in Dhaka, Bangladesh, with over 7 years of enterprise experience specializing in cybersecurity strategy, governance, and security operations within the financial sector. Currently serving as a Senior IT Security Engineer at ASA Bangladesh, he oversees organization-wide security frameworks, leads Security Operations Center (SOC) activities, and directs comprehensive Vulnerability Assessment and Penetration Testing (VAPT) initiatives. His past professional background includes security leadership and engineering roles at organizations such as BRAC Bank PLC, IDLC Finance PLC, and Brain Station-23 PLC, where he managed SIEM/XDR systems, led digital forensics investigations, and conducted penetration testing for global corporate clients. Recognized before as an official Wazuh Ambassador, he holds a Master of Science in Telecommunications and Internet Technologies from FH Technikum Wien along with certifications including Mile2 (C)PTE, AWS Cloud Security Specialty, and APIsec Certified Practitioner.'],
            ['Faisal Imroz Heemel', 'Mentor & Industrial Expert, MERN Stack & React.js', 'faisal-imroz-heemel.webp', null],
            ['Tasnia Hossain Tonni', 'Communication Officer', 'tasnia-hossain-tonni.webp', 'I am a Communication Officer with 1+ years of experience, specializing in creating impactful, audience-centered messaging that enhances engagement and brand presence. With expertise in strategic communication, digital platforms, and content development, I design and execute responsive, SEO-driven campaigns that support organizational goals and reach targeted audiences. I’m dedicated to crafting scalable communication strategies that transform ideas into effective, memorable experiences.'],
            ['Rahmat Ullah', 'Digital Marketer & Trainer', 'rahmat-ullah.png', 'I’m a Digital Marketing Specialist with over 3+ years of hands-on experience, with proven skills in SEO, Facebook Ads, Google Ads, content strategy, and conversion-focused lead generation. I help brands attract the right audience, increase engagement, and turn traffic into sales using ethical, data-driven marketing techniques'],
            ['Imam Hasan', 'Accounts Executive', 'imam-hasan.webp', 'Responsible for managing company accounts, financial reporting, budgeting, payroll processing, tax compliance, and financial analysis to ensure sustainable business growth and financial transparency'],
            ['Bishwa Nath Roy', 'Sr. Web Developer', 'bishwa-nath-roy.webp', 'As a dedicated WordPress web developer with over 3 years of experience, I specialize in crafting tailored websites that meet unique client goals. With strong expertise in PHP, JavaScript, and the WordPress ecosystem, I build responsive, SEO-focused sites designed to captivate users and enhance engagement. I am passionate about delivering high-quality, scalable solutions that bring client visions to life in the digital space.'],
            ['Tarek Islam', 'Web Developer', 'tarek-islam.webp', 'I am a WordPress web developer with 1+ years of experience, specializing in creating customized, high-performance websites that deliver both functionality and visual appeal. With expertise in PHP, JavaScript, and WordPress, I design responsive, SEO-driven sites that engage users and support business growth. I’m dedicated to building scalable solutions that transform client ideas into effective digital presences.'],
            ['AR RAFY', 'Motion Graphics Designer & Video Editor', 'ar-rafy.png', 'I am a professional Video Editor and Motion Graphics Designer with 5+ years of experience in creating engaging, high-quality visual content. He specializes in video editing, motion graphics, visual storytelling, and creative content production. With a strong eye for detail and a passion for modern visual design, he is committed to delivering creative and impactful video solutions.'],
            ['Md Harun Ur Rashid', 'IT Officer & IT Expert', 'md-harun-ur-rashid.webp', 'I am a Lab Specialist with over 3 years of experience, specializing in precise testing, analysis, and lab management to support high-quality results. Skilled in data interpretation, laboratory protocols, and quality control, I ensure accurate and reliable outcomes for each project. My commitment to excellence and safety in lab operations enables me to contribute effectively to advancements and discoveries in the field.'],
        ];

        foreach ($team as $order => [$name, $designation, $photo, $bio]) {
            TeamMember::updateOrCreate(['slug' => str($name)->slug()], [
                'name' => $name, 'designation' => $designation, 'bio' => $bio,
                'photo' => "images/team/{$photo}", 'sort_order' => $order,
            ]);
        }
    }

    // "Our Partner and Collaboration" logos (files in public/images/logos)
    private function partners(): void
    {
        $logos = [
            'Bing' => 'bing-logo-150x120.webp', 'Amar Porikha' => 'amar-porikha-150x120.webp',
            'Tech Pro' => 'tech-pro-logo-150x120.webp', 'Sunshine' => 'sunshine-logo-150x120.webp',
            'Microsoft' => 'microsoft-logo-150x120.webp', 'PAMT' => 'pamt-logo-150x120.webp',
            'Midas' => 'midas-logo-150x120.webp', 'European IT Solutions' => 'Euitsols-150x120.webp',
            'Barguna Polytechnic Institute' => 'barguna-poly-150x150.webp',
            'Brahmanbaria Polytechnic Institute' => 'brahmanbaria-polytechnic-150x150.webp',
            'Chapainawabganj Polytechnic Institute' => 'chapainawabgoanj-polytechnic-150x150.webp',
            'Chittagong Mohila Polytechnic Institute' => 'chittagoang-mohila-polytechnic.webp',
            'Chittagong Polytechnic Institute' => 'chittagoang-polytechnic-150x150.webp',
            "Cox's Bazar Polytechnic Institute" => 'cox-bazar-polytechnic-150x150.webp',
            'Dinajpur Polytechnic Institute' => 'dinajpur-polytechnic.webp',
            'Faridpur Polytechnic Institute' => 'faridpur-polytechnic-150x150.webp',
            'Feni Computer Institute' => 'Feni-computer-institute-150x150.webp',
            'Gopalganj Polytechnic Institute' => 'gopalgoanj-polytechnic-150x150.webp',
            'Graphic Arts Institute' => 'graphic-art-institute-150x150.webp',
            'Kishoreganj Polytechnic Institute' => 'kishorgoanj-polytechnic-150x150.webp',
            'Kurigram Polytechnic Institute' => 'kurigram-polytechnic-150x150.webp',
            'Kushtia Polytechnic Institute' => 'kustia-polytechnic-150x150.webp',
            'Mymensingh Polytechnic Institute' => 'moimansing-polytechnic-150x150.webp',
            'Moulvibazar Polytechnic Institute' => 'moulvibazar-polytechnic-150x150.webp',
            'Naogaon Polytechnic Institute' => 'naogoan-polytechnic-150x150.webp',
            'Narsingdi Polytechnic Institute' => 'narsigdi-polytechnic-150x150.webp',
            'Pabna Polytechnic Institute' => 'pabna-polytechnic-150x150.webp',
            'Patuakhali Polytechnic Institute' => 'potuakhali-polytechnic-150x150.webp',
            'Rangpur Polytechnic Institute' => 'rangpur-polytechnic-150x150.webp',
            'Sherpur Polytechnic Institute' => 'sherpur-polytechnic-150x150.webp',
            'Thakurgaon Polytechnic Institute' => 'thakurgaon-polytechnic-logo-150x150.webp',
            'Jhenaidah Polytechnic Institute' => 'jhenaidah-polytechnic-institute-150x150.webp',
            'Habiganj Polytechnic Institute' => 'hpilogo-150x146.webp',
            'ADB' => 'adb-logo-150x120.webp', 'BASIS' => 'basis-logo.svg',
            'Government of Bangladesh' => 'gov-logo-150x120.webp',
        ];

        $order = 0;
        foreach ($logos as $name => $file) {
            Partner::updateOrCreate(['name' => $name], ['logo' => "images/logos/{$file}", 'sort_order' => $order++]);
        }
    }
}
