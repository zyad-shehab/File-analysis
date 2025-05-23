<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Document;
use Smalot\PdfParser\Parser;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Str;
use App\Models\OperationTime;
use PhpOffice\PhpWord\IOFactory;

class DocumentController extends Controller{


public function uploadForm()
    {
        return view('Document.upload');
    }



public function upload(Request $request){
        $request->validate([
            'file' => 'required|mimes:pdf,docx|max:20480',
            
        ]);
        $startTime = microtime(true);

        $file = $request->file('file');
        $path = $file->store('documents');
    //new code with docx && pdf
    $extension = $file->getClientOriginalExtension();
    $text = '';

    if ($extension === 'pdf') {
        $parser = new Parser();
        $pdf = $parser->parseFile(storage_path("app/{$path}"));
        $text = $pdf->getText();
        try {
            $parser = new Parser();
            $pdf = $parser->parseFile(storage_path("app/{$path}"));
            $text = $pdf->getText();
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['file' => 'حدث خطأ أثناء قراءة ملف PDF: ' . $e->getMessage()]);
        }
    } elseif ($extension === 'docx') {
        try {
            $phpWord = IOFactory::load(storage_path("app/{$path}"));
            $text = '';
            foreach ($phpWord->getSections() as $section) {
                foreach ($section->getElements() as $element) {
                    if (method_exists($element, 'getText')) {
                        $text .= $element->getText() . "\n";
                    }
                }
            }
            if (empty($text)) {
                return redirect()->back()->withErrors(['file' => 'لم يتم العثور على نص في ملف Word']);
            }
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['file' => 'حدث خطأ أثناء قراءة ملف Word: ' . $e->getMessage()]);
        }}
    if (empty($text)) {
        return redirect()->back()->withErrors(['file' => 'تعذر استخراج النص من الملف.']);
    }
        //للتصنيف
        $category = $this->detectCategory($text); 
        // استخراج العنوان ( أول سطر)
        $lines = preg_split("/\r\n|\n|\r/", trim($text));
        $title = $lines[0] ?? 'unknown';

        // حفظ في قاعدة البيانات
        $doc= Document::query()->create([
            'title'=>$title,
            'content' => $text,
            'file_path' => $path,
            'size' => $file->getSize(),
            'category'=>$category
        ]);
        $endTime = microtime(true);
        $searchTime = round($endTime - $startTime, 4); // per sce

    OperationTime::create([
        'operation' => 'classify',
        'time' => $searchTime,
    ]);
        return redirect()->back()->with('success', 'تم رفع الملف وتحليله بنجاح');
    }

    

public function download($id){
    try {
        $document = Document::findOrFail($id);
        
        if (!Storage::exists($document->file_path)) {
            return redirect()->back()->withErrors(['error' => 'الملف غير موجود']);
        }

        return Storage::download($document->file_path, $document->title);
    } catch (\Exception $e) {
        return redirect()->back()->withErrors(['error' => 'حدث خطأ أثناء تنزيل الملف']);
    }
}


public function search(Request $request){

    $startTime = microtime(true);

    $keyword = $request->input('q');
    $words = array_filter(explode(' ', $keyword), fn($word) => trim($word) !== '');

    $documents = Document::query()
        ->when(!empty($words), function ($query) use ($words) {
            $query->where(function ($q) use ($words) {
                foreach ($words as $word) {
                    $q->orWhere('title', 'like', "%{$word}%")
                      ->orWhere('content', 'like', "%{$word}%");
                }
            });
        })
        ->get();
  
    $endTime = microtime(true);
    $searchTime = round($endTime - $startTime, 4); 

    OperationTime::create([
        'operation' => 'search',
        'time' => $searchTime,
    ]);
    return view('document.search_results', compact('documents', 'keyword'));
    }





    private function detectCategory($text){
        $categories = [
        'CV'=>['profile','contact','skills'],
        'Education' => ['school', 'student', 'teacher', 'university', 'curriculum', 'learning', 'classroom'],
        'Health' => ['doctor', 'medicine', 'disease', 'hospital', 'treatment', 'health', 'clinic'],
        'Politics' => ['election', 'government', 'policy', 'president', 'congress', 'minister', 'vote'],
        'Economy' => ['economy', 'finance', 'money', 'trade', 'market', 'investment', 'bank'],
        'Technology' => ['Java', 'PHP', 'SQL',
        'HTML', 'CSS', 'JavaScript','computer', 'software', 'AI', 'data', 'technology', 'application', 'programming'],
        'Environment' => ['climate', 'pollution', 'environment', 'sustainability', 'recycling', 'emissions'],
        'Health_Nutrition' => [
        'nutrition', 'diet', 'vitamins', 'minerals', 'healthy eating', 'balanced diet',
        'macronutrients', 'micronutrients', 'calories', 'protein', 'carbohydrates',
        'fats', 'fiber', 'hydration', 'meal plan', 'weight loss', 'weight gain',
        'intermittent fasting', 'keto', 'vegan', 'vegetarian', 'paleo', 'superfoods',
        'whole foods', 'processed foods', 'organic', 'gluten-free', 'sugar-free',
        'cholesterol', 'heart health', 'blood sugar', 'insulin', 'diabetes diet',
        'food pyramid', 'BMI', 'body composition', 'supplements', 'sports nutrition',
        'pre-workout', 'post-workout', 'omega 3', 'probiotics', 'gut health',
        'digestive system', 'metabolism', 'food labels', 'eating disorder', 'anorexia',
        'bulimia', 'nutritionist', 'dietitian', 'meal prep', 'clean eating'],
        'Health_MentalHealth' => [
        'mental health', 'psychology', 'therapy', 'counseling', 'depression', 'anxiety',
        'stress', 'PTSD', 'bipolar', 'schizophrenia', 'mental illness', 'CBT',
        'psychotherapy', 'mindfulness', 'meditation', 'self-care', 'self-esteem',
        'trauma', 'emotions', 'emotional regulation', 'mental well-being',
        'mental disorders', 'diagnosis', 'mental healthcare', 'support group',
        'burnout', 'grief', 'suicide prevention', 'psychiatrist', 'psychologist',
        'mood disorder', 'thought disorder', 'mental breakdown', 'mental resilience',
        'coping skills', 'mental capacity', 'mental fatigue', 'mental clarity'],
        'Politics_Elections' => [
        'election', 'vote', 'voting', 'ballot', 'campaign', 'political party', 'candidate',
        'voter turnout', 'election fraud', 'debate', 'presidential election',
        'parliament', 'congress', 'senate', 'governor', 'mayor', 'electoral college',
        'poll', 'polling station', 'register to vote', 'voting machine', 'vote count',
        'absentee ballot', 'mail-in vote', 'election day', 'political platform'],
        'Politics_International' => [
        'diplomacy', 'treaty', 'sanctions', 'united nations', 'embassy', 'foreign policy',
        'international relations', 'geopolitics', 'world leaders', 'summit', 'NATO',
        'war', 'peace talks', 'international law', 'refugee', 'asylum', 'global conflict',
        'border', 'sovereignty', 'territory dispute', 'international aid'],
        'Environment_ClimateChange' => [
        'climate change', 'global warming', 'carbon emissions', 'greenhouse gases',
        'climate crisis', 'climate action', 'carbon footprint', 'sea level rise',
        'melting ice', 'drought', 'wildfires', 'deforestation', 'pollution',
        'environmental impact', 'climate scientist', 'IPCC', 'fossil fuels',
        'renewable energy', 'climate agreement', 'carbon neutral', 'net zero'],
        'Environment_Sustainability' => [
        'sustainability', 'renewables', 'green energy', 'solar power', 'wind power',
        'eco-friendly', 'sustainable living', 'recycling', 'composting', 'plastic ban',
        'green tech', 'green building', 'water conservation', 'zero waste', 'carbon offset',
        'sustainable agriculture', 'biodiversity', 'clean water', 'energy efficiency' ],
        'Education_Higher' => [
        'university', 'college', 'campus', 'bachelor', 'master', 'phd', 'higher education',
        'academic', 'curriculum', 'lecture', 'professor', 'exam', 'grade', 'GPA',
        'tuition', 'scholarship', 'student loan', 'research paper', 'academic journal',
        'thesis', 'dissertation', 'internship', 'academic year'],
        'Education_OnlineLearning' => [
        'e-learning', 'online course', 'MOOC', 'LMS', 'distance learning',
        'virtual classroom', 'remote education', 'video lecture', 'self-paced learning',
        'online degree', 'edtech', 'digital learning', 'interactive learning',
        'webinar', 'elearning platform', 'online exam', 'certification course'],'Technology_AI' => [
        'artificial intelligence', 'AI', 'machine learning', 'deep learning', 'neural networks',
        'natural language processing', 'computer vision', 'reinforcement learning',
        'supervised learning', 'unsupervised learning', 'data mining', 'predictive analytics',
        'robotics', 'automation', 'chatbot', 'intelligent system', 'algorithm',
        'AI ethics', 'big data', 'tensorflow', 'pytorch', 'classification', 'clustering',
        'feature extraction', 'pattern recognition', 'speech recognition', 'image recognition'],
        'Technology_Cybersecurity' => [
        'cybersecurity', 'firewall', 'encryption', 'malware', 'ransomware', 'phishing',
        'virus', 'trojan', 'spyware', 'hacking', 'penetration testing', 'vulnerability',
        'threat detection', 'data breach', 'intrusion detection', 'network security',
        'access control', 'two-factor authentication', 'cyber attack', 'DDoS',
        'zero-day exploit', 'antivirus', 'patch management', 'security protocol',
        'SSL', 'TLS', 'VPN', 'ethical hacking', 'cyber defense', 'incident response'],
        'Business_Finance' => [
        'finance', 'investment', 'stocks', 'bonds', 'portfolio', 'asset management',
        'mutual funds', 'dividends', 'capital gains', 'interest rate', 'inflation',
        'banking', 'loan', 'credit score', 'mortgage', 'budgeting', 'tax', 'accounting',
        'financial planning', 'retirement', 'insurance', 'cryptocurrency', 'bitcoin',
        'blockchain', 'forex', 'financial market', 'economy', 'GDP', 'recession',
        'stock exchange', 'venture capital', 'private equity'],
        'Business_Marketing' => [
        'marketing', 'advertising', 'branding', 'digital marketing', 'SEO', 'social media',
        'content marketing', 'email marketing', 'lead generation', 'customer engagement',
        'market research', 'campaign', 'conversion rate', 'affiliate marketing',
        'influencer marketing', 'product launch', 'public relations', 'analytics',
        'ecommerce', 'sales funnel', 'PPC', 'Google Ads', 'Facebook Ads', 'retargeting'],
        'Sports_Football' => [
        'football', 'soccer', 'goal', 'penalty', 'offside', 'corner kick', 'free kick',
        'world cup', 'FIFA', 'league', 'team', 'coach', 'player', 'stadium',
        'championship', 'match', 'tournament', 'referee', 'red card', 'yellow card',
        'dribble', 'pass', 'goalkeeper', 'striker', 'midfielder', 'defender'],
        'Sports_Basketball' => [
        'basketball', 'NBA', 'slam dunk', 'three-pointer', 'free throw', 'dribble',
        'rebound', 'assist', 'block', 'coach', 'player', 'team', 'match', 'tournament',
        'championship', 'court', 'foul', 'point guard', 'center', 'forward'],
        'Entertainment_Movies' => [
        'movie', 'film', 'director', 'actor', 'actress', 'screenplay', 'cinema',
        'hollywood', 'blockbuster', 'genre', 'drama', 'comedy', 'thriller', 'horror',
        'animation', 'documentary', 'award', 'oscar', 'festival', 'box office',
        'sequel', 'prequel', 'trailer', 'release date'],
        'Entertainment_Music' => [
        'music', 'song', 'album', 'artist', 'band', 'concert', 'genre', 'pop', 'rock',
        'jazz', 'classical', 'hip hop', 'rap', 'electronic', 'festival', 'award',
        'Grammy', 'lyrics', 'chart', 'single', 'record label'],
        'Travel_Tourism' => [
        'travel', 'tourism', 'vacation', 'holiday', 'hotel', 'resort', 'flight',
        'destination', 'tour guide', 'backpacking', 'itinerary', 'travel insurance',
        'passport', 'visa', 'adventure', 'cruise', 'sightseeing', 'culture',
        'local cuisine', 'transportation'] ,
        'Travel_Tourism' => [
        'travel', 'tourism', 'vacation', 'holiday', 'hotel', 'resort', 'flight',
        'destination', 'tour guide', 'backpacking', 'itinerary', 'travel insurance',
        'passport', 'visa', 'adventure', 'cruise', 'sightseeing', 'culture',
        'local cuisine', 'transportation', 'travel agency', 'road trip', 'beach',
        'mountains', 'tourist attraction', 'travel blog', 'budget travel', 'luxury travel',
        'travel restrictions', 'travel advisory'],
        'Science_Space' => ['space', 'astronomy', 'planet', 'star', 'galaxy', 'black hole', 'cosmology',
        'NASA', 'space exploration', 'rocket', 'satellite', 'space station',
        'asteroid', 'comet', 'telescope', 'universe', 'space mission', 'space technology',
        'exoplanet', 'gravity', 'orbit', 'solar system', 'spacewalk', 'mars mission',
        'space probe', 'spacesuit', 'interstellar', 'astronaut'],
        'Science_Physics' => ['physics', 'quantum mechanics', 'relativity', 'particle physics', 'gravity',
        'energy', 'force', 'motion', 'thermodynamics', 'electricity', 'magnetism',
        'wave', 'optics', 'nuclear physics', 'string theory', 'dark matter', 'black hole',
        'mass', 'velocity', 'acceleration', 'momentum', 'mechanics', 'fluid dynamics',
        'atomic physics', 'cosmology'],
        'Technology_SoftwareDevelopment' => ['software development', 'programming', 'coding', 'debugging', 'agile',
        'scrum', 'devops', 'version control', 'git', 'continuous integration',
        'deployment', 'backend', 'frontend', 'fullstack', 'API', 'framework',
        'library', 'testing', 'unit test', 'code review', 'software architecture',
        'database', 'SQL', 'NoSQL', 'cloud computing', 'microservices', 'containerization',
        'docker', 'kubernetes'],
        'Technology_Hardware' => ['hardware', 'CPU', 'GPU', 'RAM', 'motherboard', 'storage', 'SSD', 'HDD',
        'processor', 'chipset', 'circuit', 'semiconductor', 'sensor', 'IoT',
        'peripherals', 'motherboard', 'network card', 'power supply', 'cooling system',
        'assembly', 'overclocking', 'embedded systems', 'firmware', 'BIOS'],
        'Culture_Art' => ['art', 'painting', 'sculpture', 'gallery', 'museum', 'contemporary art',
        'modern art', 'classical art', 'artist', 'exhibition', 'canvas', 'portrait',
        'abstract', 'installations', 'fine arts', 'digital art', 'performance art',
        'art history', 'art criticism', 'public art'],
        'Culture_Literature' => ['literature', 'novel', 'poetry', 'author', 'fiction', 'non-fiction',
        'short story', 'essay', 'biography', 'memoir', 'literary criticism',
        'literary award', 'genre', 'narrative', 'prose', 'drama', 'classic literature',
        'modern literature', 'literary festival', 'publisher'],
        'Business_Entrepreneurship' => ['startup', 'entrepreneur', 'business plan', 'funding', 'venture capital',
        'pitch', 'incubator', 'accelerator', 'business model', 'scaling',
        'market research', 'customer acquisition', 'revenue', 'profit',
        'business strategy', 'innovation', 'disruption', 'angel investor',
        'seed funding', 'exit strategy', 'valuation', 'pivot', 'lean startup',
        'cash flow', 'marketing', 'branding', 'sales', 'negotiation', 'leadership',
        'team building', 'product development', 'growth hacking'],

        'Business_Marketing' => ['marketing', 'advertising', 'SEO', 'content marketing', 'social media',
        'branding', 'campaign', 'email marketing', 'influencer', 'analytics',
        'conversion rate', 'lead generation', 'PPC', 'Google Ads', 'Facebook Ads',
        'market segmentation', 'customer retention', 'public relations',
        'digital marketing', 'affiliate marketing', 'brand awareness',
        'marketing strategy', 'keyword research', 'copywriting', 'blogging',
        'video marketing', 'viral marketing', 'engagement', 'CRM', 'growth marketing'],

        'Finance_Investment' => ['investment', 'stocks', 'bonds', 'mutual funds', 'portfolio', 'dividend',
        'risk management', 'asset allocation', 'stock market', 'trading',
        'financial analysis', 'valuation', 'capital gains', 'hedge fund',
        'real estate investment', 'cryptocurrency', 'blockchain', 'forex',
        'financial planning', 'retirement plan', 'tax strategy', 'ETF',
        'commodities', 'private equity', 'venture capital', 'ROI', 'cash flow',
        'financial advisor', 'wealth management'],
        'Finance_Banking' => ['banking', 'loan', 'mortgage', 'credit card', 'interest rate',
        'savings account', 'checking account', 'debit card', 'ATM', 'wire transfer',
        'online banking', 'mobile banking', 'fraud prevention', 'financial regulation',
        'compliance', 'risk assessment', 'capital adequacy', 'investment banking',
        'retail banking', 'central bank', 'monetary policy', 'credit score',
        'underwriting', 'bank statement', 'account management'],
        'Sports_Football' => ['football', 'soccer', 'goal', 'penalty', 'offside', 'corner kick',
        'free kick', 'midfielder', 'striker', 'defender', 'goalkeeper',
        'FIFA', 'World Cup', 'league', 'championship', 'transfer', 'coach',
        'referee', 'match', 'stadium', 'fans', 'tournament', 'yellow card',
        'red card', 'substitution', 'training', 'dribbling', 'passing', 'shooting'],
        'Sports_Basketball' => ['basketball', 'NBA', 'three pointer', 'dunk', 'free throw', 'rebound',
        'assist', 'point guard', 'shooting guard', 'small forward', 'power forward',
        'center', 'coach', 'defense', 'offense', 'foul', 'timeout', 'playoff',
        'championship', 'dribble', 'slam dunk', 'alley-oop', 'crossover', 'fast break',
        'court', 'team', 'score', 'block', 'steal'],
        'Entertainment_Movies' => ['movie', 'film', 'director', 'actor', 'actress', 'screenplay',
        'cinematography', 'genre', 'box office', 'blockbuster', 'premiere',
        'trailer', 'award', 'Oscar', 'festival', 'animation', 'documentary',
        'thriller', 'comedy', 'drama', 'horror', 'science fiction', 'romance',
        'soundtrack', 'special effects', 'script', 'production', 'casting'],
        'Entertainment_Music' => ['music', 'song', 'album', 'artist', 'band', 'concert', 'festival',
        'genre', 'pop', 'rock', 'hip hop', 'jazz', 'classical', 'electronic',
        'instrument', 'lyric', 'composer', 'producer', 'record label',
        'chart', 'single', 'tour', 'performance', 'soundtrack', 'studio',
        'music video', 'award', 'Grammy'],
        
        ];

        // Convert text to lowercase for case-insensitive matching
        $text = strtolower($text);

        foreach ($categories as $category => $keywords) {
            foreach ($keywords as $keyword) {
                $smal=strtolower($keyword);
                if (str_contains($text, $smal)) {
                    return $category;
                }
            }
        }   
        return 'Uncategorized';
        }



public function list(Request $request){
    $startTime = microtime(true);

    $sortOrder = $request->get('sort', 'asc'); // 'asc'   
    $documents = Document::orderBy('title', $sortOrder)->get();

    $endTime = microtime(true);
    $searchTime = round($endTime - $startTime, 4); // per sce

    OperationTime::create([
        'operation' => 'sort',
        'time' => $searchTime,
    ]);


         //for statistics
        $totalDocuments = Document::count();
        $totalSizeBytes = Document::sum('size');
        $totalSizeMB = round($totalSizeBytes / (1024 * 1024), 2);
         $averageSearchTime = OperationTime::where('operation', 'search')->avg('time');
         $averageSortTime = OperationTime::where('operation', 'sort')->avg('time');
         $averageClassifyTime = OperationTime::where('operation', 'classify')->avg('time');

    return view('Document.list', compact(
        'documents', 'sortOrder',
         'totalDocuments', 
        'totalSizeMB', 
        'averageSearchTime', 
        'averageSortTime', 
        'averageClassifyTime'
    ));
}

}
