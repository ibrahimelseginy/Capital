/* ============================================================
   Seven Tech Capital — App JS
   Theme, language/RTL, nav, reveal, small interactions
   ============================================================ */
(function () {
  'use strict';

  /* ---------- Theme ---------- */
  const root = document.documentElement;
  // The blocking script in <head> already set data-theme before paint (no FOUC).
  // This mirrors it as a fallback and follows the OS preference when nothing is saved.
  function preferredTheme() {
    const saved = localStorage.getItem('stc-theme');
    if (saved === 'dark' || saved === 'light') return saved;
    return window.matchMedia && matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
  }
  if (!root.getAttribute('data-theme')) root.setAttribute('data-theme', preferredTheme());

  // Keep the browser chrome (address/status bar) colour in sync with the live theme.
  function syncThemeColor() {
    const dark = root.getAttribute('data-theme') === 'dark';
    let m = document.querySelector('meta[name="theme-color"]:not([media])');
    if (!m) { m = document.createElement('meta'); m.setAttribute('name', 'theme-color'); document.head.appendChild(m); }
    m.setAttribute('content', dark ? '#0D0D0D' : '#FAF7F3');
  }

  window.toggleTheme = function () {
    const cur = root.getAttribute('data-theme');
    const next = cur === 'dark' ? 'light' : 'dark';
    root.setAttribute('data-theme', next);
    localStorage.setItem('stc-theme', next);
    syncThemeIcon();
    syncThemeColor();
    document.querySelectorAll('button[onclick*="toggleTheme"]').forEach(b => b.setAttribute('aria-pressed', String(next === 'dark')));
  };

  function syncThemeIcon() {
    const dark = root.getAttribute('data-theme') === 'dark';
    document.querySelectorAll('[data-theme-icon]').forEach(el => {
      el.innerHTML = dark
        ? '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.2" y1="4.2" x2="5.6" y2="5.6"/><line x1="18.4" y1="18.4" x2="19.8" y2="19.8"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.2" y1="19.8" x2="5.6" y2="18.4"/><line x1="18.4" y1="5.6" x2="19.8" y2="4.2"/></svg>'
        : '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>';
    });
  }

  /* ---------- Language / RTL ---------- */
  const LANGS = { ar: { dir: 'rtl', label: 'العربية' }, en: { dir: 'ltr', label: 'English' }, fr: { dir: 'ltr', label: 'Français' } };
  const I18N = {
    en: {
      'تخطّي إلى المحتوى الرئيسي': 'Skip to main content',
      'الرئيسية': 'Home',
      'من نحن': 'About',
      'المستثمرون': 'Investors',
      'رواد الأعمال': 'Entrepreneurs',
      'القطاعات': 'Sectors',
      'قصص النجاح': 'Success stories',
      'الأخبار': 'News',
      'الأخبار والفعاليات': 'News & events',
      'تواصل': 'Contact',
      'تواصل معنا': 'Contact us',
      'دخول': 'Login',
      'سجّل': 'Register',
      'سجّل كمستثمر': 'Register as an investor',
      'صندوق قيد التأسيس': 'Fund in formation',
      'MENA والخليج': 'MENA and GCC',
      'نطاق إقليمي للنمو': 'Regional growth scope',
      'نَبني المشروع قبل أن نُفعّل': 'We build the venture before activating',
      'الاستثمار.': 'investment.',
      'نَبني المشروع قبل أن نُفعّل الاستثمار.': 'We build the venture before activating investment.',
      'Seven Tech Capital صندوق استثماري مدعوم بذراع تقني بخبرة تمتد إلى 20 عامًا في تأسيس وتشغيل الشركات التقنية. نختبر الفكرة، نبني المنتج، ونُجهّز المشروع للتشغيل قبل تفعيل رأس المال — لتقليل مخاطر التنفيذ وصناعة فرص أكثر جاهزية للنمو.': 'Seven Tech Capital is an investment fund backed by a technology arm with 20 years of experience building and operating technology companies. We validate the idea, build the product, and prepare the venture for operation before activating capital, reducing execution risk and creating opportunities that are more ready to grow.',
      'بوابات مراجعة قبل التمويل': 'Review gates before funding',
      'ذراع تقني للتنفيذ': 'Technology arm for execution',
      'متابعة شفافة للمحفظة': 'Transparent portfolio tracking',
      'قدّم مشروعك الآن': 'Submit your venture now',
      'CAPITAL · STATUS': 'CAPITAL · STATUS',
      'سجل جاهزية رأس المال': 'Capital readiness ledger',
      'مُقفَل': 'Locked',
      'لا يتم تحرير التمويل إلا بعد مرور المشروع ببوابات التحقق والبناء والتشغيل.': 'Funding is released only after the venture passes validation, build, and operation gates.',
      'الفكرة والتحقق': 'Idea & validation',
      'بحث المشكلة والسوق واختبار الفرضيات': 'Problem, market, and assumption testing',
      'مُجتاز': 'Passed',
      'الدراسة والتأسيس': 'Study & setup',
      'النموذج المالي والقانوني وخطة العمل': 'Financial model, legal setup, and business plan',
      'البناء التقني': 'Technical build',
      'MVP · ويب · موبايل · ذكاء اصطناعي وتكاملات': 'MVP · web · mobile · AI and integrations',
      'جارٍ': 'In progress',
      'التمويل والتوظيف': 'Funding & hiring',
      'هيكلة الجولة وبناء الفريق الأساسي': 'Round structuring and core team setup',
      'بانتظار': 'Pending',
      'التشغيل — يُفعَّل رأس المال': 'Operation — capital activates',
      'عند الجاهزية للتشغيل يُفتح القفل ويُحرَّر التمويل': 'When operation-ready, the lock opens and funding is released',
      'الإفراج': 'Release',
      'تفعيل رأس المال': 'Capital activation',
      'قيد الإعداد': 'Preparing',
      'عامًا خبرة تراكمية للفريق': 'Years of cumulative team experience',
      'عامًا عمر شركة Seven Tech': 'Years since Seven Tech was founded',
      'قيمة مشروعات شارك بها الفريق': 'Value of projects the team contributed to',
      'عميل في مصر والدول العربية': 'Clients in Egypt and Arab markets',
      'لماذا Seven Tech Capital': 'Why Seven Tech Capital',
      'استثمار يمر ببوابات مراجعة وجاهزية قبل تحرير التمويل': 'Investment that passes readiness gates before funding is released',
      'نحن لا نكتفي بتمويل الفكرة، بل نبنيها ونُجهّزها ونُشغّلها — بخبرة تقنية وتشغيلية عميقة ومجلس استشاري قوي.': 'We do not just fund the idea. We build it, prepare it, and operate it with deep technical and operational experience and a strong advisory board.',
      'آمن ومدروس': 'Secure and considered',
      'إظهار أن الاستثمار يمر ببوابات مراجعة وجاهزية وتشغيل قبل تحرير التمويل.': 'Investment passes review, readiness, and operation gates before funding is released.',
      'تقني ومستقبلي': 'Technical and future-ready',
      'تجربة رقمية حديثة، حركة هادئة، وبيانات واضحة — ليست مجرد مؤثرات بصرية.': 'A modern digital experience, calm motion, and clear data, not visual effects for their own sake.',
      'جريء وواثق': 'Bold and confident',
      'رسائل مباشرة ومساحات واسعة وتباين قوي، مع البرتقالي كإشارة قرار.': 'Direct messaging, generous space, strong contrast, and orange as the decision signal.',
      'مؤسسي وشفاف': 'Institutional and transparent',
      'عرض المراحل والحالات والتقارير والمستندات وسجل الإجراءات بوضوح.': 'Clear stages, statuses, reports, documents, and action logs.',
      'القطاعات المستهدفة': 'Target sectors',
      'نستثمر في القطاعات التي نفهمها ونبنيها': 'We invest in sectors we understand and can build',
      'نركّز على قطاعات قابلة للبناء والقياس، حيث يمكن للذراع التقني تحويل الفكرة إلى منتج وتشغيل فعلي.': 'We focus on sectors that can be built and measured, where the technology arm can turn an idea into a product and real operations.',
      'استعرض كل القطاعات': 'Explore all sectors',
      'البرمجيات و SaaS': 'Software & SaaS',
      'منصات ومنتجات برمجية قابلة للتوسع.': 'Scalable software platforms and products.',
      'التقنية المالية': 'Fintech',
      'حلول مالية وأسواق مال ومدفوعات.': 'Financial solutions, capital markets, and payments.',
      'الذكاء الاصطناعي': 'Artificial intelligence',
      'التنبؤ والتحليلات والأتمتة.': 'Prediction, analytics, and automation.',
      'الصحة الرقمية': 'Digital health',
      'منصات رعاية وخدمات صحية.': 'Care platforms and health services.',
      'التعليم التقني': 'EdTech',
      'منصات تعلم ومهارات رقمية.': 'Learning platforms and digital skills.',
      'إنترنت الأشياء': 'Internet of Things',
      'أجهزة وشبكات ذكية متصلة.': 'Connected smart devices and networks.',
      'التوصيل واللوجستيات': 'Delivery & logistics',
      'سلاسل إمداد وتوصيل ذكية.': 'Smart supply chains and delivery systems.',
      'التحول الرقمي': 'Digital transformation',
      'إدارة وتشغيل وأتمتة العمليات.': 'Managing, operating, and automating workflows.',
      'أمثلة الفرص': 'Opportunity examples',
      'مسارات المنصة': 'Platform paths',
      'مساران، منصة واحدة، سجل تدقيق كامل': 'Two paths, one platform, full audit trail',
      'اختر المسار المناسب لك. كل رحلة لها متطلبات واضحة، حالات متابعة، وخطوة تالية ظاهرة داخل المنصة.': 'Choose the right path. Each journey has clear requirements, visible statuses, and a next step inside the platform.',
      'للمستثمرين': 'For investors',
      'استثمر في فرص أكثر جاهزية': 'Invest in more ready opportunities',
      'تأهيل يدوي، اتفاقيات سرية، فرص محمية، ومتابعة كاملة للمحفظة عبر لوحة تحكم مخصصة.': 'Manual qualification, confidentiality agreements, protected opportunities, and full portfolio tracking through a dedicated dashboard.',
      'تأهيل KYC/AML يدوي محكم خلال 3–5 أيام': 'Careful manual KYC/AML review within 3-5 days',
      'فرص لا تُعرض إلا بعد الاعتماد وتوقيع NDA': 'Opportunities shown only after approval and NDA signing',
      'خيارات: الصندوق · فرصة محددة · مسار هجين': 'Options: fund · specific opportunity · hybrid path',
      'متابعة المحفظة والتقارير والاجتماعات': 'Portfolio, reports, and meeting tracking',
      'ابدأ كمستثمر': 'Start as an investor',
      'لرواد الأعمال': 'For entrepreneurs',
      'من الفكرة إلى التشغيل والتوسع': 'From idea to operation and scale',
      'اختر نوع الدعم المناسب، قدّم مشروعك، وتابع مراحل التقييم والاجتماعات والمهام من لوحة واحدة.': 'Choose the right support, submit your venture, and track evaluation, meetings, and tasks from one place.',
      'نموذج تقديم متعدد الخطوات مع حفظ تلقائي': 'Multi-step application with autosave',
      'رفع Pitch Deck ودراسة جدوى وملفات': 'Upload pitch deck, feasibility study, and files',
      'خط مراجعة شفاف: جديد ← تقييم ← قرار ← تعاقد': 'Transparent pipeline: new -> evaluation -> decision -> contract',
      'دعم يشمل البناء والتمويل والتشغيل': 'Support covering build, funding, and operations',
      'قدّم مشروعك': 'Submit your venture',
      'قصص النجاح': 'Success stories',
      'دراسات حالة موثقة — دون أسماء العملاء': 'Verified case studies without client names',
      'نستخدم قصصًا مجهّلة لعرض أثر البناء التقني والتشغيلي دون كشف بيانات العملاء أو الفرص الحساسة.': 'We use anonymized stories to show the impact of technical and operational build without exposing client data or sensitive opportunities.',
      'استعرض كل القصص': 'Explore all stories',
      'معاينة': 'Preview',
      'منصة مدفوعات B2B': 'B2B payments platform',
      'خفض زمن التسوية وتحسين تجربة التاجر عبر بنية حديثة.': 'Reduced settlement time and improved merchant experience through modern architecture.',
      'زمن العملية': 'Process time',
      'نمو المعاملات': 'Transaction growth',
      'للإطلاق': 'To launch',
      'منصة حجوزات ورعاية': 'Booking and care platform',
      'توحيد تجربة المريض ورقمنة العمليات التشغيلية.': 'Unified patient experience and digitized operational workflows.',
      'مستخدمون': 'Users',
      'التكلفة': 'Cost',
      'نظام توصيل ذكي': 'Smart delivery system',
      'تحسين المسارات والتتبع اللحظي وأتمتة الأسطول.': 'Route optimization, live tracking, and fleet automation.',
      'كفاءة': 'Efficiency',
      'زمن التسليم': 'Delivery time',
      'تُعرض المؤشرات بعد التحقق والمراجعة القانونية والتسويقية. الأرقام أعلاه تمثيلية في هذا النموذج.': 'Metrics are shown after legal, marketing, and validation review. The figures above are illustrative in this prototype.',
      'آخر المستجدات': 'Latest updates',
      'تحديثات المنصة، منهجية الاستثمار، والشراكات المؤسسية في مكان واحد.': 'Platform updates, investment methodology, and institutional partnerships in one place.',
      'عرض كل المستجدات': 'View all updates',
      'فعالية': 'Event',
      'إطلاق الإصدار التشغيلي الأول للمنصة': 'Launch of the first operational platform release',
      'نستعرض بوابتَي المستثمر ورائد الأعمال ولوحة الإدارة.': 'A walkthrough of the investor portal, entrepreneur portal, and admin dashboard.',
      'مقال': 'Article',
      'منهجية تقليل مخاطر التنفيذ في الاستثمار الجريء': 'A methodology for reducing execution risk in venture investment',
      'كيف نُجهّز المشروع للتشغيل قبل تفعيل رأس المال.': 'How we prepare a venture for operation before activating capital.',
      'شراكة': 'Partnership',
      'توسع مؤسسي مستهدف نحو السعودية والإمارات': 'Targeted institutional expansion toward Saudi Arabia and the UAE',
      'خطة التوسع الجغرافي مع فصل بيانات كل دولة.': 'Geographic expansion plan with separated data by country.',
      'اقرأ المزيد': 'Read more',
      'ابدأ الرحلة المناسبة لك': 'Start the path that fits you',
      'جاهز لتبدأ رحلتك الاستثمارية؟': 'Ready to start your investment journey?',
      'سجّل كمستثمر معتمد أو قدّم مشروعك اليوم. تأهيل يدوي محكم، سرية كاملة، وتجربة رقمية شفافة بثلاث لغات.': 'Register as a qualified investor or submit your venture today. Careful manual qualification, full confidentiality, and a transparent digital experience in three languages.',
      'صندوق استثماري مدعوم بذراع تقني بخبرة تمتد إلى 20 عامًا. نبني المشروع ونُجهّزه للتشغيل قبل تفعيل رأس المال.': 'An investment fund backed by a technology arm with 20 years of experience. We build and prepare ventures for operation before activating capital.',
      'كيان قيد التأسيس واستكمال التراخيص. لا يُستقبل أي تمويل فعلي قبل اعتماد المستشار القانوني وحساب الضمان.': 'Entity in formation and licensing process. No actual funding is accepted before legal advisor approval and escrow account setup.',
      'المنصة': 'Platform',
      'الشركة': 'Company',
      'الذراع التقني': 'Technology arm',
      'قانوني': 'Legal',
      'الشروط والأحكام': 'Terms and conditions',
      'سياسة الخصوصية': 'Privacy policy',
      'إخلاء المسؤولية': 'Disclaimer',
      'سياسة KYC/AML': 'KYC/AML policy',
      'ملفات تعريف الارتباط': 'Cookies',
      'تسجيل الدخول': 'Login',
      '© 2026 Seven Tech Capital — جميع الحقوق محفوظة.': '© 2026 Seven Tech Capital — All rights reserved.',
      'Angular · Laravel REST API · AWS — نطاق: MENA والخليج': 'Angular · Laravel REST API · AWS — Scope: MENA and GCC'
    },
    fr: {
      'تخطّي إلى المحتوى الرئيسي': 'Aller au contenu principal',
      'الرئيسية': 'Accueil',
      'من نحن': 'A propos',
      'المستثمرون': 'Investisseurs',
      'رواد الأعمال': 'Entrepreneurs',
      'القطاعات': 'Secteurs',
      'قصص النجاح': 'Cas de reussite',
      'الأخبار': 'Actualites',
      'الأخبار والفعاليات': 'Actualites et evenements',
      'تواصل': 'Contact',
      'تواصل معنا': 'Nous contacter',
      'دخول': 'Connexion',
      'سجّل': 'S inscrire',
      'سجّل كمستثمر': 'S inscrire comme investisseur',
      'صندوق قيد التأسيس': 'Fonds en creation',
      'MENA والخليج': 'MENA et Golfe',
      'نطاق إقليمي للنمو': 'Portee regionale de croissance',
      'نَبني المشروع قبل أن نُفعّل': 'Nous construisons le projet avant d activer',
      'الاستثمار.': 'l investissement.',
      'نَبني المشروع قبل أن نُفعّل الاستثمار.': 'Nous construisons le projet avant d activer l investissement.',
      'Seven Tech Capital صندوق استثماري مدعوم بذراع تقني بخبرة تمتد إلى 20 عامًا في تأسيس وتشغيل الشركات التقنية. نختبر الفكرة، نبني المنتج، ونُجهّز المشروع للتشغيل قبل تفعيل رأس المال — لتقليل مخاطر التنفيذ وصناعة فرص أكثر جاهزية للنمو.': 'Seven Tech Capital est un fonds d investissement soutenu par une branche technologique forte de 20 ans d experience dans la creation et l exploitation d entreprises technologiques. Nous validons l idee, construisons le produit et preparons le projet a l operation avant d activer le capital.',
      'بوابات مراجعة قبل التمويل': 'Etapes de revue avant financement',
      'ذراع تقني للتنفيذ': 'Branche technologique d execution',
      'متابعة شفافة للمحفظة': 'Suivi transparent du portefeuille',
      'قدّم مشروعك الآن': 'Soumettre votre projet',
      'سجل جاهزية رأس المال': 'Registre de preparation du capital',
      'مُقفَل': 'Verrouille',
      'لا يتم تحرير التمويل إلا بعد مرور المشروع ببوابات التحقق والبناء والتشغيل.': 'Le financement n est libere qu apres validation, construction et preparation operationnelle.',
      'الفكرة والتحقق': 'Idee et validation',
      'بحث المشكلة والسوق واختبار الفرضيات': 'Analyse du probleme, du marche et des hypotheses',
      'مُجتاز': 'Valide',
      'الدراسة والتأسيس': 'Etude et mise en place',
      'النموذج المالي والقانوني وخطة العمل': 'Modele financier, cadre juridique et plan d affaires',
      'البناء التقني': 'Construction technique',
      'MVP · ويب · موبايل · ذكاء اصطناعي وتكاملات': 'MVP · web · mobile · IA et integrations',
      'جارٍ': 'En cours',
      'التمويل والتوظيف': 'Financement et recrutement',
      'هيكلة الجولة وبناء الفريق الأساسي': 'Structuration du tour et equipe coeur',
      'بانتظار': 'En attente',
      'التشغيل — يُفعَّل رأس المال': 'Operation — activation du capital',
      'عند الجاهزية للتشغيل يُفتح القفل ويُحرَّر التمويل': 'Quand le projet est operationnel, le capital est libere',
      'الإفراج': 'Liberation',
      'تفعيل رأس المال': 'Activation du capital',
      'قيد الإعداد': 'En preparation',
      'لماذا Seven Tech Capital': 'Pourquoi Seven Tech Capital',
      'استثمار يمر ببوابات مراجعة وجاهزية قبل تحرير التمويل': 'Un investissement qui passe par des etapes de preparation avant liberation du financement',
      'نحن لا نكتفي بتمويل الفكرة، بل نبنيها ونُجهّزها ونُشغّلها — بخبرة تقنية وتشغيلية عميقة ومجلس استشاري قوي.': 'Nous ne finançons pas seulement l idee. Nous la construisons, la preparons et l operons avec une expertise technique et operationnelle solide.',
      'آمن ومدروس': 'Securise et etudie',
      'تقني ومستقبلي': 'Technologique et tourne vers l avenir',
      'جريء وواثق': 'Audacieux et confiant',
      'مؤسسي وشفاف': 'Institutionnel et transparent',
      'القطاعات المستهدفة': 'Secteurs cibles',
      'نستثمر في القطاعات التي نفهمها ونبنيها': 'Nous investissons dans les secteurs que nous comprenons et construisons',
      'استعرض كل القطاعات': 'Voir tous les secteurs',
      'البرمجيات و SaaS': 'Logiciels et SaaS',
      'التقنية المالية': 'Fintech',
      'الذكاء الاصطناعي': 'Intelligence artificielle',
      'الصحة الرقمية': 'Sante numerique',
      'التعليم التقني': 'EdTech',
      'إنترنت الأشياء': 'Internet des objets',
      'التوصيل واللوجستيات': 'Livraison et logistique',
      'التحول الرقمي': 'Transformation numerique',
      'أمثلة الفرص': 'Exemples d opportunites',
      'مسارات المنصة': 'Parcours de la plateforme',
      'مساران، منصة واحدة، سجل تدقيق كامل': 'Deux parcours, une plateforme, une piste d audit complete',
      'للمستثمرين': 'Pour les investisseurs',
      'استثمر في فرص أكثر جاهزية': 'Investir dans des opportunites plus preparees',
      'ابدأ كمستثمر': 'Commencer comme investisseur',
      'لرواد الأعمال': 'Pour les entrepreneurs',
      'من الفكرة إلى التشغيل والتوسع': 'De l idee a l operation et a la croissance',
      'قدّم مشروعك': 'Soumettre votre projet',
      'قصص النجاح': 'Cas de reussite',
      'دراسات حالة موثقة — دون أسماء العملاء': 'Etudes de cas verifiees sans noms de clients',
      'استعرض كل القصص': 'Voir tous les cas',
      'معاينة': 'Aperçu',
      'زمن العملية': 'Temps du processus',
      'نمو المعاملات': 'Croissance des transactions',
      'للإطلاق': 'Pour lancer',
      'مستخدمون': 'Utilisateurs',
      'التكلفة': 'Cout',
      'كفاءة': 'Efficacite',
      'زمن التسليم': 'Delai de livraison',
      'آخر المستجدات': 'Dernieres nouvelles',
      'عرض كل المستجدات': 'Voir toutes les nouvelles',
      'فعالية': 'Evenement',
      'مقال': 'Article',
      'شراكة': 'Partenariat',
      'اقرأ المزيد': 'Lire la suite',
      'ابدأ الرحلة المناسبة لك': 'Commencez le bon parcours',
      'جاهز لتبدأ رحلتك الاستثمارية؟': 'Pret a commencer votre parcours d investissement ?',
      'المنصة': 'Plateforme',
      'الشركة': 'Entreprise',
      'الذراع التقني': 'Branche technologique',
      'قانوني': 'Juridique',
      'تسجيل الدخول': 'Connexion'
    }
  };
  const originalTextNodes = new WeakMap();
  const savedLang = localStorage.getItem('stc-lang') || 'ar';
  applyLang(savedLang, false);

  window.setLang = function (code) {
    applyLang(code, true);
    document.querySelectorAll('.dropdown.open').forEach(d => d.classList.remove('open'));
  };

  function applyLang(code, notify) {
    if (!LANGS[code]) code = 'ar';
    localStorage.setItem('stc-lang', code);
    root.setAttribute('lang', code);
    root.setAttribute('dir', LANGS[code].dir);
    translatePage(code);
    document.querySelectorAll('[data-lang]').forEach(b => {
      const on = b.getAttribute('data-lang') === code;
      b.classList.toggle('active', on);
      b.setAttribute('aria-checked', String(on));
    });
    document.querySelectorAll('[data-lang-label]').forEach(el => el.textContent = code.toUpperCase());
    if (notify) toast(code === 'ar' ? 'تم تغيير اللغة إلى العربية.' : (code === 'en' ? 'Language changed to English.' : 'Langue changee en francais.'));
  }

  function translatePage(code) {
    const dict = I18N[code] || {};
    const walker = document.createTreeWalker(document.body, NodeFilter.SHOW_TEXT, {
      acceptNode(node) {
        const parent = node.parentElement;
        if (!parent || ['SCRIPT', 'STYLE', 'NOSCRIPT', 'SVG'].includes(parent.tagName)) return NodeFilter.FILTER_REJECT;
        return node.nodeValue.trim() ? NodeFilter.FILTER_ACCEPT : NodeFilter.FILTER_REJECT;
      }
    });
    const nodes = [];
    while (walker.nextNode()) nodes.push(walker.currentNode);
    nodes.forEach(node => {
      if (!originalTextNodes.has(node)) originalTextNodes.set(node, node.nodeValue);
      const original = originalTextNodes.get(node);
      const key = original.trim().replace(/\s+/g, ' ');
      const next = code === 'ar' ? key : (dict[key] || I18N.en[key] || key);
      const leading = original.match(/^\s*/)[0];
      const trailing = original.match(/\s*$/)[0];
      node.nodeValue = leading + next + trailing;
    });
  }

  /* ---------- Dropdowns ---------- */
  function setDropdown(dd, open) {
    dd.classList.toggle('open', open);
    const btn = dd.querySelector('[data-dropdown]');
    if (btn) btn.setAttribute('aria-expanded', String(open));
  }
  document.addEventListener('click', function (e) {
    const trigger = e.target.closest('[data-dropdown]');
    const own = trigger ? trigger.closest('.dropdown') : null;
    const willOpen = own && !own.classList.contains('open');
    document.querySelectorAll('.dropdown.open').forEach(d => { if (d !== own) setDropdown(d, false); });
    if (own) setDropdown(own, willOpen);
  });

  /* ---------- Nav scroll ---------- */
  const nav = document.querySelector('.nav');
  if (nav) {
    const onScroll = () => nav.classList.toggle('scrolled', window.scrollY > 8);
    onScroll(); window.addEventListener('scroll', onScroll, { passive: true });
  }

  /* ---------- Overlays: mobile menu + dashboard sidebar ---------- */
  let lastFocus = null;
  function focusFirst(container) {
    const f = container.querySelector('a, button, input, select, [tabindex]:not([tabindex="-1"])');
    if (f) f.focus();
  }
  function setOverlay(el, toggleSelector, open) {
    const scrim = document.querySelector('.scrim');
    el.classList.toggle('open', open);
    if (scrim) scrim.classList.toggle('show', open);
    document.body.style.overflow = open ? 'hidden' : '';
    document.querySelectorAll(toggleSelector).forEach(b => b.setAttribute('aria-expanded', String(open)));
    if (open) { lastFocus = document.activeElement; focusFirst(el); }
    else if (lastFocus) { try { lastFocus.focus(); } catch (e) {} lastFocus = null; }
  }

  /* ---------- Mobile menu ---------- */
  window.toggleMobileMenu = function () {
    const m = document.querySelector('.mobile-menu');
    if (!m) return;
    setOverlay(m, '.menu-toggle', !m.classList.contains('open'));
  };

  /* ---------- Sidebar (dashboard) ---------- */
  window.toggleSidebar = function () {
    const sb = document.querySelector('.sidebar');
    if (!sb) return;
    setOverlay(sb, '.side-toggle', !sb.classList.contains('open'));
  };
  window.closeOverlays = function () {
    let had = false;
    document.querySelectorAll('.sidebar.open, .mobile-menu.open').forEach(el => { el.classList.remove('open'); had = true; });
    document.querySelectorAll('.scrim.show').forEach(el => el.classList.remove('show'));
    document.querySelectorAll('.menu-toggle[aria-expanded="true"], .side-toggle[aria-expanded="true"]').forEach(b => b.setAttribute('aria-expanded', 'false'));
    document.body.style.overflow = '';
    if (had && lastFocus) { try { lastFocus.focus(); } catch (e) {} lastFocus = null; }
  };

  /* ---------- Keyboard: Escape closes overlays; Tab is trapped inside an open one ---------- */
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
      document.querySelectorAll('.dropdown.open').forEach(d => setDropdown(d, false));
      if (document.querySelector('.mobile-menu.open, .sidebar.open')) closeOverlays();
      return;
    }
    if (e.key === 'Tab') {
      const overlay = document.querySelector('.mobile-menu.open, .sidebar.open');
      if (!overlay) return;
      const items = overlay.querySelectorAll('a, button, input, select, [tabindex]:not([tabindex="-1"])');
      if (!items.length) return;
      const first = items[0], last = items[items.length - 1];
      if (e.shiftKey && document.activeElement === first) { e.preventDefault(); last.focus(); }
      else if (!e.shiftKey && document.activeElement === last) { e.preventDefault(); first.focus(); }
    }
  });

  /* ---------- Accordion ---------- */
  window.toggleAcc = function (btn) {
    const item = btn.closest('.acc-item');
    const body = item.querySelector('.acc-a');
    const open = item.classList.toggle('open');
    btn.setAttribute('aria-expanded', String(open));
    body.setAttribute('aria-hidden', String(!open));
    // Drop any pending "hide after collapse" from a rapid re-toggle.
    if (body._accHide) { body.removeEventListener('transitionend', body._accHide); body._accHide = null; }
    if (open) {
      body.removeAttribute('hidden');                 // restore layout before measuring height
      body.style.maxHeight = body.scrollHeight + 'px';
    } else {
      body.style.maxHeight = '0';
      const reduce = window.matchMedia && matchMedia('(prefers-reduced-motion: reduce)').matches;
      if (reduce) {
        body.setAttribute('hidden', '');              // no collapse animation to wait for
      } else {
        // Keep it displayed through the collapse, then remove it from layout + the a11y tree.
        body._accHide = function (ev) {
          if (ev.target !== body || ev.propertyName !== 'max-height') return;
          if (!item.classList.contains('open')) body.setAttribute('hidden', '');
          body.removeEventListener('transitionend', body._accHide);
          body._accHide = null;
        };
        body.addEventListener('transitionend', body._accHide);
      }
    }
  };

  /* ---------- Roving-tabindex keyboard helper (radiogroup / tablist) ---------- */
  // Selection-follows-focus: arrows move focus AND activate; Enter/Space activate; Home/End jump.
  // RTL-aware so ArrowRight/ArrowLeft map to the correct visual neighbour in the Arabic layout.
  function isRTL(el) {
    try { return getComputedStyle(el).direction === 'rtl'; }
    catch (e) { return (document.documentElement.getAttribute('dir') || document.dir) === 'rtl'; }
  }
  function rovingKey(e, items, activate) {
    const el = e.currentTarget, k = e.key, idx = items.indexOf(el);
    if (k === 'Enter' || k === ' ' || k === 'Spacebar') { e.preventDefault(); activate(el); return; }
    if (idx < 0 || items.length < 2) return;
    let to;
    if (k === 'Home') to = 0;
    else if (k === 'End') to = items.length - 1;
    else {
      const rtl = isRTL(el);
      let d = 0;
      if (k === 'ArrowDown') d = 1;
      else if (k === 'ArrowUp') d = -1;
      else if (k === 'ArrowRight') d = rtl ? -1 : 1;
      else if (k === 'ArrowLeft') d = rtl ? 1 : -1;
      else return;
      to = (idx + d + items.length) % items.length;
    }
    e.preventDefault();
    activate(items[to]);
    items[to].focus();
  }

  /* ---------- Tabs (role=tab, roving tabindex) ---------- */
  window.switchTab = function (el, group) {
    document.querySelectorAll('[data-tabgroup="' + group + '"] .tab').forEach(t => {
      const on = t === el;
      t.classList.toggle('active', on);              // keep the CSS .active cue
      t.setAttribute('aria-selected', String(on));
      t.setAttribute('tabindex', on ? '0' : '-1');   // roving tab-stop
    });
    const target = el.getAttribute('data-target');
    document.querySelectorAll('[data-tabpanel="' + group + '"]').forEach(p => p.classList.add('hide'));
    const panel = document.getElementById(target);
    if (panel) panel.classList.remove('hide');
  };
  function bindTabs() {
    document.querySelectorAll('[data-tabgroup]').forEach(groupEl => {
      const items = Array.prototype.slice.call(groupEl.querySelectorAll('.tab'));
      if (!items.length) return;
      const group = groupEl.getAttribute('data-tabgroup');
      const active = items.filter(t => t.classList.contains('active'))[0] || items[0];
      items.forEach(t => {
        t.setAttribute('tabindex', t === active ? '0' : '-1');
        if (!t.hasAttribute('aria-selected')) t.setAttribute('aria-selected', String(t === active));
        t.addEventListener('keydown', e => rovingKey(e, items, tab => window.switchTab(tab, group)));
      });
    });
  }

  /* ---------- Investor type selector (role=radio, roving tabindex) ---------- */
  function itypeGroup(el) { return el.closest('[role="radiogroup"]') || el.parentElement; }
  function itypeItems(el) { return Array.prototype.slice.call(itypeGroup(el).querySelectorAll('.itype')); }
  window.pickType = function (el) {
    itypeItems(el).forEach(i => {
      const on = i === el;
      i.classList.toggle('sel', on);                 // keep the CSS .sel cue
      i.setAttribute('aria-checked', String(on));
      i.setAttribute('tabindex', on ? '0' : '-1');   // roving tab-stop
    });
  };
  function bindTypeSelectors() {
    const groups = [];
    document.querySelectorAll('.itype').forEach(el => {
      el.addEventListener('keydown', e => rovingKey(e, itypeItems(el), t => window.pickType(t)));
      const g = itypeGroup(el);
      if (groups.indexOf(g) === -1) groups.push(g);
    });
    groups.forEach(g => {
      const items = Array.prototype.slice.call(g.querySelectorAll('.itype'));
      if (!items.length) return;
      const active = items.filter(i => i.classList.contains('sel'))[0] || items[0];
      items.forEach(i => {
        i.setAttribute('tabindex', i === active ? '0' : '-1');
        if (!i.hasAttribute('aria-checked')) i.setAttribute('aria-checked', String(i === active));
      });
    });
  }

  /* ---------- Auth segment ---------- */
  window.pickSeg = function (el, role) {
    el.parentElement.querySelectorAll('button').forEach(b => b.classList.remove('active'));
    el.classList.add('active');
    document.querySelectorAll('[data-role-panel]').forEach(p => p.classList.add('hide'));
    const panel = document.querySelector('[data-role-panel="' + role + '"]');
    if (panel) panel.classList.remove('hide');
  };

  /* ---------- Modal Dialog System ---------- */
  window.openModal = function (title, contentHtml) {
    let backdrop = document.getElementById('stc-modal-backdrop');
    if (!backdrop) {
      backdrop = document.createElement('div');
      backdrop.id = 'stc-modal-backdrop';
      backdrop.className = 'modal-backdrop';
      backdrop.innerHTML = '<div class="modal-dialog" role="dialog" aria-modal="true"><button type="button" class="modal-close" onclick="closeModal()" aria-label="إغلاق"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:20px;height:20px;"><path d="M18 6L6 18M6 6l12 12"/></svg></button><div class="modal-header"><h3 id="stc-modal-title"></h3></div><div class="modal-body" id="stc-modal-body"></div></div>';
      document.body.appendChild(backdrop);
      backdrop.addEventListener('click', function(e) {
        if (e.target === backdrop) closeModal();
      });
    }
    document.getElementById('stc-modal-title').textContent = title || '';
    document.getElementById('stc-modal-body').innerHTML = contentHtml || '';
    backdrop.classList.add('open');
    document.body.style.overflow = 'hidden';
  };

  window.closeModal = function () {
    const backdrop = document.getElementById('stc-modal-backdrop');
    if (backdrop) backdrop.classList.remove('open');
    document.body.style.overflow = '';
  };

  window.showStoryModal = function (title, category, desc, statsStr) {
    let stats = [];
    try { stats = JSON.parse(statsStr); } catch (e) {}
    let statsHtml = '<div class="modal-stat-grid">';
    stats.forEach(s => {
      statsHtml += `<div><b style="font-family:var(--font-head);font-size:22px;color:var(--orange);display:block;">${s[0]}</b><span style="font-size:12px;color:var(--text-2);">${s[1]}</span></div>`;
    });
    statsHtml += '</div>';

    const body = `
      <div style="display:flex;gap:8px;margin-bottom:12px;">
        <span class="badge badge-orange">${category}</span>
        <span class="badge badge-success">موثقة ومُعتمَدة</span>
      </div>
      <p style="color:var(--text-2);font-size:15px;line-height:1.7;">${desc}</p>
      ${statsHtml}
      <div class="modal-note">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>
        <span>تمت دراسة هذه الحالة والتأكد من المؤشرات عبر الفريق التقني والمالي لـ Seven Tech Capital.</span>
      </div>
      <button class="btn btn-primary btn-block mt-24" onclick="closeModal();location.href='login.php?tab=register';">سجّل كمستثمر للاطلاع على المزيد من الفرص المشابهة</button>
    `;
    openModal(title, body);
  };

  /* ---------- Instant Table Search Filter ---------- */
  window.filterTable = function (inputId, tableId) {
    const input = document.getElementById(inputId);
    const table = document.getElementById(tableId);
    if (!input || !table) return;
    const q = input.value.toLowerCase().trim();
    const rows = table.querySelectorAll('tbody tr');
    rows.forEach(tr => {
      const text = tr.textContent.toLowerCase();
      tr.style.display = text.includes(q) ? '' : 'none';
    });
  };

  /* ---------- Toast ---------- */
  let toastTimer;
  window.toast = function (msg) {
    let t = document.getElementById('stc-toast');
    if (!t) {
      t = document.createElement('div');
      t.id = 'stc-toast';
      t.setAttribute('role', 'status');
      t.setAttribute('aria-live', 'polite');
      document.body.appendChild(t);
    }
    t.innerHTML = `<svg viewBox="0 0 24 24" fill="none" stroke="var(--orange)" stroke-width="2.5" style="width:18px;height:18px;flex:none"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg><span>${msg}</span>`;
    requestAnimationFrame(() => { t.style.opacity = '1'; t.style.transform = 'none'; });
    clearTimeout(toastTimer);
    toastTimer = setTimeout(() => { t.style.opacity = '0'; t.style.transform = 'translateY(16px)'; }, 3400);
  };

  /* ---------- Demo action guard ---------- */
  function isDashboardPage() {
    return !!document.querySelector('.app .sidebar');
  }

  function esc(str) {
    return String(str == null ? '' : str).replace(/[&<>"']/g, function (ch) {
      return ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' })[ch];
    });
  }

  function dashboardActionFromText(text, msg) {
    const raw = (msg || text || '').replace(/\s+/g, ' ').trim();
    if (/رفع|Upload|file|مستند|الملف/i.test(raw)) return 'upload';
    if (/اعتماد|قبول|approve/i.test(raw)) return 'approve';
    if (/رفض|reject/i.test(raw)) return 'reject';
    if (/حفظ|save/i.test(raw)) return 'save';
    if (/إرسال|رسالة|مراسلة|reply|send/i.test(raw)) return 'message';
    if (/تذكرة|دعم|support/i.test(raw)) return 'ticket';
    if (/اجتماع|جدولة|موعد|calendar|meeting/i.test(raw)) return 'meeting';
    if (/دخول الاجتماع|رابط الاجتماع/i.test(raw)) return 'join';
    if (/تفاصيل|فتح|عرض|مراجعة|إدارة|خيارات/i.test(raw)) return 'details';
    return 'generic';
  }

  function actionLabel(type) {
    const labels = {
      upload: 'رفع ملف',
      approve: 'اعتماد الطلب',
      reject: 'رفض الطلب',
      save: 'حفظ التغييرات',
      message: 'إرسال رسالة',
      ticket: 'إنشاء تذكرة دعم',
      meeting: 'إدارة اجتماع',
      join: 'دخول الاجتماع',
      details: 'عرض التفاصيل',
      generic: 'تنفيذ الإجراء'
    };
    return labels[type] || labels.generic;
  }

  function storeOperation(type, label, meta) {
    const key = 'stc-ops-log';
    let rows = [];
    try { rows = JSON.parse(localStorage.getItem(key) || '[]'); } catch (e) {}
    rows.unshift({
      type: type,
      label: label,
      meta: meta || '',
      page: location.pathname.split('/').pop(),
      at: new Date().toISOString()
    });
    localStorage.setItem(key, JSON.stringify(rows.slice(0, 80)));
  }

  function completeOperation(type, label, target) {
    storeOperation(type, label, target && target.textContent ? target.textContent.trim().slice(0, 90) : '');
    const modalBody = document.getElementById('stc-modal-body');
    const modalNote = modalBody ? (modalBody.querySelector('textarea') ? modalBody.querySelector('textarea').value.trim() : '') : '';
    if (target) {
      const row = target.closest('tr, .support-ticket, .detail-card, .feed-item');
      if (row) row.classList.add('op-complete');
      if ((type === 'approve' || type === 'save') && row) {
        const badge = row.querySelector('.badge');
        if (badge) {
          badge.className = 'badge badge-success';
          badge.textContent = type === 'approve' ? 'معتمد' : 'محفوظ';
        }
      }
    }
    applyDashboardMutation(type, label, target, modalNote);
    closeModal();
    toast('تم تنفيذ العملية وتسجيلها في سجل الإجراءات.');
  }

  function applyDashboardMutation(type, label, target, note) {
    const scope = document.querySelector('.page-body') || document.body;
    function findPanel(pattern) {
      const panels = Array.prototype.slice.call(scope.querySelectorAll('.panel'));
      return panels.filter(function (p) { return pattern.test(p.textContent); })[0] || null;
    }
    if (type === 'message') {
      const conversationPanel = scope.querySelector('#reply') ? scope.querySelector('#reply').closest('.panel') : null;
      const holder = conversationPanel ? conversationPanel.querySelector('.panel-body') : null;
      if (holder) {
        const msg = document.createElement('div');
        msg.className = 'message-thread mine op-complete';
        msg.innerHTML = '<b>أنت</b><p>' + esc(note || label || 'تم إرسال الرسالة.') + '</p><small>الآن</small>';
        const field = holder.querySelector('.field');
        holder.insertBefore(msg, field || holder.firstChild);
      }
    }
    if (type === 'ticket') {
      const panel = (target && target.closest('.panel')) || findPanel(/تذاكر الدعم|الدعم/);
      const body = panel && panel.querySelector('.panel-body');
      if (body) {
        const ticket = document.createElement('div');
        ticket.className = 'support-ticket op-complete';
        ticket.innerHTML = '<div><b>#SUP-' + Math.floor(200 + Math.random() * 700) + ' · ' + esc(label || 'تذكرة جديدة') + '</b><p class="text-2">' + esc(note || 'تم إنشاء التذكرة وإرسالها لفريق الدعم.') + '</p><div class="detail-meta"><span>الأولوية: متوسطة</span><span>آخر رد: الآن</span></div></div><span class="badge badge-warning">جديدة</span>';
        const action = body.querySelector('.btn');
        body.insertBefore(ticket, action || body.firstChild);
      }
    }
    if (type === 'upload') {
      const table = scope.querySelector('table.data tbody');
      if (table && /مستند|ملف|Document|Pitch|XLSX|PDF|رفع/.test(scope.textContent)) {
        const tr = document.createElement('tr');
        tr.className = 'op-complete';
        tr.innerHTML = '<td>ملف مرفوع الآن</td><td>PDF</td><td class="mono">1.0MB</td><td>الآن</td><td><span class="badge badge-info">قيد المراجعة</span></td>';
        const cols = table.closest('table').querySelectorAll('thead th').length;
        while (tr.children.length < cols) {
          const td = document.createElement('td');
          td.innerHTML = tr.children.length === cols - 1 ? '<span class="badge badge-info">قيد المراجعة</span>' : 'النظام';
          tr.appendChild(td);
        }
        table.insertBefore(tr, table.firstChild);
      }
    }
    if (type === 'meeting') {
      const panel = (target && target.closest('.panel')) || findPanel(/اجتماع|مواعيد/);
      const body = panel && panel.querySelector('.panel-body');
      if (body) {
        const card = document.createElement('div');
        card.className = 'meeting-card mt-16 op-complete';
        card.innerHTML = '<div class="meeting-date"><b>24</b><span>يوليو</span></div><div><h4>' + esc(label || 'اجتماع جديد') + '</h4><p class="text-2">تم حفظ الموعد · Google Meet · الآن</p></div>';
        body.insertBefore(card, body.firstChild);
      }
    }
    if (type === 'join') {
      const a = document.createElement('a');
      a.href = 'https://meet.google.com/';
      a.target = '_blank';
      a.rel = 'noopener';
      document.body.appendChild(a);
      a.click();
      a.remove();
    }
  }

  function operationForm(type, label, context) {
    const ctx = esc(context || 'إجراء من لوحة التحكم');
    if (type === 'upload') {
      return `
        <p class="text-2">اختر الملف واربطه بالسجل المناسب. سيتم حفظ العملية في سجل الإجراءات المحلي.</p>
        <div class="field mt-16"><label class="label">نوع الملف</label><select class="select"><option>نموذج مالي</option><option>Pitch Deck</option><option>دراسة جدوى</option><option>مستند قانوني</option></select></div>
        <div class="field mt-16"><label class="label">الملف</label><input class="input" type="file"></div>
        <div class="field mt-16"><label class="label">ملاحظة</label><textarea class="textarea" rows="3" placeholder="أضف ملاحظة للمراجع...">${ctx}</textarea></div>
        <button class="btn btn-primary btn-block mt-24" data-complete-operation type="button">تأكيد الرفع</button>
      `;
    }
    if (type === 'meeting' || type === 'join') {
      return `
        <p class="text-2">إدارة الاجتماع مرتبطة بسجل المواعيد والتنبيهات.</p>
        <div class="meeting-card mt-16"><div class="meeting-date"><b>24</b><span>يوليو</span></div><div><h4>${esc(label)}</h4><p class="text-2">Google Meet · فريق الاستثمار والتشغيل</p></div></div>
        <div class="auth-two-col mt-16"><div class="field"><label class="label">التاريخ</label><input class="input" type="date" value="2026-07-24"></div><div class="field"><label class="label">الوقت</label><input class="input" type="time" value="16:00"></div></div>
        <button class="btn btn-primary btn-block mt-24" data-complete-operation type="button">${type === 'join' ? 'فتح رابط الاجتماع' : 'حفظ الموعد'}</button>
      `;
    }
    if (type === 'message') {
      return `
        <p class="text-2">اكتب رسالة للفريق المختص. سيتم تسجيلها في سجل التواصل.</p>
        <div class="field mt-16"><label class="label">القناة</label><select class="select"><option>فريق الاستثمار</option><option>الفريق التقني</option><option>الدعم الفني</option><option>الإدارة</option></select></div>
        <div class="field mt-16"><label class="label">الرسالة</label><textarea class="textarea" rows="5" placeholder="اكتب رسالتك...">${ctx}</textarea></div>
        <button class="btn btn-primary btn-block mt-24" data-complete-operation type="button">إرسال الرسالة</button>
      `;
    }
    if (type === 'ticket') {
      return `
        <p class="text-2">افتح تذكرة دعم جديدة مرتبطة بالحساب أو الطلب الحالي.</p>
        <div class="field mt-16"><label class="label">الأولوية</label><select class="select"><option>متوسطة</option><option>عالية</option><option>منخفضة</option></select></div>
        <div class="field mt-16"><label class="label">وصف المشكلة</label><textarea class="textarea" rows="5" placeholder="اشرح المطلوب...">${ctx}</textarea></div>
        <button class="btn btn-primary btn-block mt-24" data-complete-operation type="button">إنشاء التذكرة</button>
      `;
    }
    if (type === 'details') {
      return `
        <div class="detail-card"><div><span class="eyebrow">تفاصيل السجل</span><h3>${esc(label)}</h3><p>${ctx}</p><div class="detail-meta"><span>الحالة: نشط</span><span>آخر تحديث: الآن</span><span>الصلاحية: متاحة</span></div></div></div>
        <button class="btn btn-primary btn-block mt-24" data-complete-operation type="button">تمت المراجعة</button>
      `;
    }
    return `
      <p class="text-2">سيتم تنفيذ العملية وتسجيلها في سجل الإجراءات.</p>
      <div class="field mt-16"><label class="label">ملاحظات</label><textarea class="textarea" rows="4">${ctx}</textarea></div>
      <button class="btn btn-primary btn-block mt-24" data-complete-operation type="button">${type === 'approve' ? 'تأكيد الاعتماد' : (type === 'reject' ? 'تأكيد الرفض' : 'تأكيد العملية')}</button>
    `;
  }

  window.openBackendAction = function (type, label, context, target) {
    const title = actionLabel(type);
    window._lastBackendTarget = target || null;
    openModal(title, operationForm(type, label || title, context || ''));
    const btn = document.querySelector('#stc-modal-body .btn-primary');
    if (btn) {
      btn.dataset.operationType = type;
      btn.dataset.operationLabel = label || title;
      btn.onclick = function () { completeOperation(type, label || title, target || window._lastBackendTarget || document.body); };
    }
  };

  window.completeOperation = completeOperation;

  window.demoAction = function (e, msg) {
    const t = e && e.target;
    const toggle = t && t.tagName === 'INPUT' && (t.type === 'checkbox' || t.type === 'radio');
    if (e && !toggle) e.preventDefault();
    if (toggle) {
      storeOperation('toggle', 'تحديث حالة', t.checked ? 'checked' : 'unchecked');
      toast('تم تحديث الحالة وتسجيل العملية.');
      return;
    }
    if (isDashboardPage()) {
      const label = (t && t.closest('a,button') ? t.closest('a,button').textContent : '') || msg || 'إجراء';
      const type = dashboardActionFromText(label, msg);
      openBackendAction(type, label.trim() || actionLabel(type), msg || label, t && t.closest('a,button'));
      return;
    }
    toast(msg || 'تم تنفيذ الإجراء في نموذج الواجهة.');
  };

  function bindBackendActions() {
    if (!isDashboardPage()) return;
    document.addEventListener('click', function (e) {
      const el = e.target.closest('a[href="#"]:not([onclick]), button[data-backend-action]');
      if (!el) return;
      e.preventDefault();
      const label = el.getAttribute('aria-label') || el.textContent || 'إجراء';
      const type = el.getAttribute('data-backend-action') || dashboardActionFromText(label);
      openBackendAction(type, label.trim(), label, el);
    });
  }

  /* ---------- Reveal on scroll ---------- */
  const io = 'IntersectionObserver' in window ? new IntersectionObserver((entries) => {
    entries.forEach(en => { if (en.isIntersecting) { en.target.classList.add('in'); io.unobserve(en.target); } });
  }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' }) : null;
  function bindReveal() {
    document.querySelectorAll('.reveal:not(.in)').forEach((el, i) => {
      el.style.transitionDelay = (Math.min(i % 6, 5) * 60) + 'ms';
      if (io) io.observe(el); else el.classList.add('in');
    });
  }

  /* ---------- Animate bars when visible ---------- */
  function bindBars() {
    document.querySelectorAll('.bars').forEach(group => {
      const obs = new IntersectionObserver((ents) => {
        ents.forEach(en => {
          if (en.isIntersecting) {
            group.querySelectorAll('.bar').forEach(b => { b.style.height = b.getAttribute('data-h') + '%'; });
            obs.disconnect();
          }
        });
      }, { threshold: 0.3 });
      obs.observe(group);
    });
  }

  /* ---------- Count up ---------- */
  function bindCounters() {
    const reduce = window.matchMedia && matchMedia('(prefers-reduced-motion: reduce)').matches;
    document.querySelectorAll('[data-count]').forEach(el => {
      const target = parseFloat(el.getAttribute('data-count'));
      const suffix = el.getAttribute('data-suffix') || '';
      const prefix = el.getAttribute('data-prefix') || '';
      // Prefix (e.g. "$") and suffix (e.g. "+"/"M") get their own spans so themes can accent them.
      const paint = (v) => {
        const num = (target % 1 === 0 ? Math.round(v) : v.toFixed(1)).toLocaleString('en-US');
        el.innerHTML = (prefix ? '<span class="pfx">' + prefix + '</span>' : '') + num + (suffix ? '<span class="u">' + suffix + '</span>' : '');
      };
      if (reduce) { paint(target); return; }        // reduced-motion: show the final value, no tween
      const obs = new IntersectionObserver((ents) => {
        ents.forEach(en => {
          if (en.isIntersecting) {
            const dur = 1300, start = performance.now();
            const step = (now) => {
              const p = Math.min((now - start) / dur, 1);
              const eased = 1 - Math.pow(1 - p, 3);
              paint(target * eased);
              if (p < 1) requestAnimationFrame(step);
            };
            requestAnimationFrame(step);
            obs.disconnect();
          }
        });
      }, { threshold: 0.5 });
      obs.observe(el);
    });
  }

  /* ---------- Currency formatting (Intl.NumberFormat) ---------- */
  // Financial Dashboard rule: consistent currency + decimals.
  // <span data-currency="185000" data-cur="USD" data-compact></span>
  function bindCurrency() {
    document.querySelectorAll('[data-currency]').forEach(el => {
      const val = parseFloat(el.getAttribute('data-currency'));
      if (isNaN(val)) return;
      const cur = el.getAttribute('data-cur') || 'USD';
      const compact = el.hasAttribute('data-compact');
      const fmt = new Intl.NumberFormat('en-US', {
        style: 'currency', currency: cur,
        notation: compact ? 'compact' : 'standard',
        maximumFractionDigits: compact ? 1 : 0
      });
      el.textContent = fmt.format(val);
    });
  }

  /* ---------- Sparklines ---------- */
  // <svg class="sparkline" data-spark="40,52,48,68,82,95"></svg>
  function bindSparklines() {
    document.querySelectorAll('.sparkline[data-spark]').forEach(svg => {
      const pts = svg.getAttribute('data-spark').split(',').map(Number);
      if (pts.length < 2) return;
      const w = 100, h = 30, pad = 3;
      const min = Math.min(...pts), max = Math.max(...pts), range = (max - min) || 1;
      const coords = pts.map((p, i) => {
        const x = pad + (i / (pts.length - 1)) * (w - pad * 2);
        const y = h - pad - ((p - min) / range) * (h - pad * 2);
        return x.toFixed(1) + ',' + y.toFixed(1);
      }).join(' ');
      svg.setAttribute('viewBox', '0 0 ' + w + ' ' + h);
      svg.setAttribute('preserveAspectRatio', 'none');
      svg.classList.toggle('up', pts[pts.length - 1] >= pts[0]);
      svg.classList.toggle('down', pts[pts.length - 1] < pts[0]);
      svg.innerHTML = '<polyline points="' + coords + '"></polyline>';
    });
  }

  /* ---------- Validate on blur (Forms priority) ---------- */
  // Errors are signalled by more than colour: an .invalid class, aria-invalid, and a
  // visible/announced .field-error message associated through aria-describedby.
  function bindValidation() {
    function messageFor(field) {
      const v = field.validity || {};
      if (v.valueMissing) return 'هذا الحقل مطلوب';
      if (v.typeMismatch && field.type === 'email') return 'يرجى إدخال بريد إلكتروني صحيح';
      return 'يرجى إدخال قيمة صحيحة';
    }
    function errNode(field) {
      if (field._fieldError && field._fieldError.isConnected) return field._fieldError;
      const node = document.createElement('span');
      node.className = 'field-error';
      node.id = (field.id || 'f' + Math.random().toString(36).slice(2, 8)) + '-error';
      (field.parentNode || document.body).insertBefore(node, field.nextSibling);
      field._fieldError = node;
      return node;
    }
    function describe(field, id, add) {
      const tokens = (field.getAttribute('aria-describedby') || '').split(/\s+/).filter(Boolean);
      const i = tokens.indexOf(id);
      if (add && i === -1) tokens.push(id);
      else if (!add && i !== -1) tokens.splice(i, 1);
      if (tokens.length) field.setAttribute('aria-describedby', tokens.join(' '));
      else field.removeAttribute('aria-describedby');
    }
    function showError(field) {
      const node = errNode(field);
      node.textContent = messageFor(field);
      field.classList.add('invalid');
      field.setAttribute('aria-invalid', 'true');
      describe(field, node.id, true);
    }
    function clearError(field) {
      field.classList.remove('invalid');
      field.setAttribute('aria-invalid', 'false');
      if (field._fieldError) {
        describe(field, field._fieldError.id, false);
        field._fieldError.remove();
        field._fieldError = null;
      }
    }
    document.querySelectorAll('form [required]').forEach(field => {
      field.addEventListener('blur', function () {
        if (field.checkValidity()) clearError(field);
        else showError(field);
      });
      field.addEventListener('input', function () {
        if (field.checkValidity()) clearError(field);
      });
    });
  }

  /* ---------- Back to top ---------- */
  function initBackToTop() {
    if (document.querySelector('.to-top')) return;
    const btn = document.createElement('button');
    btn.type = 'button';
    btn.className = 'to-top';
    btn.setAttribute('aria-label', 'العودة إلى أعلى الصفحة');
    btn.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M12 19V5M5 12l7-7 7 7"/></svg>';
    const reduce = window.matchMedia && matchMedia('(prefers-reduced-motion: reduce)').matches;
    btn.addEventListener('click', () => window.scrollTo({ top: 0, behavior: reduce ? 'auto' : 'smooth' }));
    document.body.appendChild(btn);
    const onScroll = () => btn.classList.toggle('show', window.scrollY > 520);
    onScroll(); window.addEventListener('scroll', onScroll, { passive: true });
  }

  /* ---------- Dashboard notifications ---------- */
  function initNotifications() {
    const center = document.querySelector('[data-notification-center]');
    if (!center) return;

    const trigger = center.querySelector('.notification-trigger');
    const panel = center.querySelector('.notification-panel');
    const countBadge = center.querySelector('.notification-count');
    const unreadLabel = center.querySelector('[data-unread-count]');
    const readAll = center.querySelector('.notification-read-all');
    const empty = center.querySelector('.notification-empty');
    const items = Array.prototype.slice.call(center.querySelectorAll('[data-notification-id]'));
    const scope = center.getAttribute('data-notification-scope') || 'investor';
    const storageKey = 'stc-notifications-read-' + scope;
    let readIds = [];

    try {
      const saved = JSON.parse(localStorage.getItem(storageKey) || '[]');
      if (Array.isArray(saved)) readIds = saved.map(String);
    } catch (e) {}

    function save() {
      try { localStorage.setItem(storageKey, JSON.stringify(readIds)); } catch (e) {}
    }

    function sync() {
      let unread = 0;
      items.forEach(function (item) {
        const isUnread = readIds.indexOf(item.getAttribute('data-notification-id')) === -1;
        item.classList.toggle('unread', isUnread);
        if (isUnread) unread += 1;
      });
      if (countBadge) {
        countBadge.textContent = String(unread);
        countBadge.classList.toggle('is-zero', unread === 0);
        countBadge.setAttribute('aria-label', unread + ' إشعارات جديدة');
      }
      if (unreadLabel) unreadLabel.textContent = String(unread);
      if (readAll) readAll.disabled = unread === 0;
      if (empty) empty.hidden = items.length > 0;
      trigger.setAttribute('aria-label', unread ? 'فتح الإشعارات، ' + unread + ' غير مقروءة' : 'فتح الإشعارات، لا توجد إشعارات جديدة');
    }

    function markRead(item) {
      const id = item && item.getAttribute('data-notification-id');
      if (!id || readIds.indexOf(id) !== -1) return;
      readIds.push(id);
      save();
      sync();
    }

    function setOpen(open) {
      panel.hidden = !open;
      trigger.setAttribute('aria-expanded', String(open));
      center.classList.toggle('open', open);
      if (open) {
        const firstUnread = center.querySelector('.notification-item.unread') || center.querySelector('.notification-item');
        if (firstUnread) requestAnimationFrame(function () { firstUnread.focus(); });
      }
    }

    trigger.addEventListener('click', function (event) {
      event.stopPropagation();
      setOpen(panel.hidden);
    });

    if (readAll) {
      readAll.addEventListener('click', function () {
        readIds = items.map(function (item) { return item.getAttribute('data-notification-id'); });
        save();
        sync();
      });
    }

    items.forEach(function (item) {
      item.addEventListener('click', function () { markRead(item); });
    });

    document.addEventListener('click', function (event) {
      if (!panel.hidden && !center.contains(event.target)) setOpen(false);
    });
    document.addEventListener('keydown', function (event) {
      if (event.key === 'Escape' && !panel.hidden) {
        setOpen(false);
        trigger.focus();
      }
    });

    sync();
  }

  /* ---------- Init ---------- */
  document.addEventListener('DOMContentLoaded', function () {
    syncThemeIcon();
    syncThemeColor();
    document.querySelectorAll('button[onclick*="toggleTheme"]').forEach(b => b.setAttribute('aria-pressed', String(root.getAttribute('data-theme') === 'dark')));
    document.querySelectorAll('.menu-toggle, .side-toggle').forEach(b => { if (!b.hasAttribute('aria-expanded')) b.setAttribute('aria-expanded', 'false'); });
    bindReveal();
    bindBars();
    bindCounters();
    bindCurrency();
    bindSparklines();
    bindValidation();
    bindTabs();
    bindTypeSelectors();
    bindBackendActions();
    initBackToTop();
    initNotifications();
    bindStoryFilters();
    bindRipple();
    // Accordion: sync ARIA + collapsed state from the .open class at load.
    document.querySelectorAll('.acc-item').forEach(item => {
      const q = item.querySelector('.acc-q');
      const body = item.querySelector('.acc-a');
      if (!body) return;
      const open = item.classList.contains('open');
      body.style.maxHeight = open ? body.scrollHeight + 'px' : '0';
      if (q) q.setAttribute('aria-expanded', String(open));
      body.setAttribute('aria-hidden', String(!open));
      if (open) body.removeAttribute('hidden'); else body.setAttribute('hidden', '');
    });
  });

  /* ---------- Story filters (success-stories.php) ---------- */
  function bindStoryFilters() {
    var group = document.querySelector('[data-filter-group]');
    if (!group) return;
    var chips = Array.prototype.slice.call(group.querySelectorAll('.chip[data-filter]'));
    var cards = Array.prototype.slice.call(document.querySelectorAll('[data-category]'));
    if (!chips.length || !cards.length) return;

    function activate(chip) {
      var cat = chip.getAttribute('data-filter');
      chips.forEach(function (c) {
        var on = c === chip;
        c.classList.toggle('active', on);
        c.setAttribute('aria-checked', String(on));
        c.setAttribute('tabindex', on ? '0' : '-1');
      });
      cards.forEach(function (card) {
        var show = cat === 'all' || card.getAttribute('data-category') === cat;
        card.classList.toggle('filter-hide', !show);
      });
    }

    chips.forEach(function (c) {
      c.addEventListener('click', function () { activate(c); });
      c.addEventListener('keydown', function (e) {
        rovingKey(e, chips, activate);
      });
    });

    // Initialise: first chip is active
    var initial = chips.filter(function(c) { return c.classList.contains('active'); })[0] || chips[0];
    chips.forEach(function (c) {
      c.setAttribute('tabindex', c === initial ? '0' : '-1');
      c.setAttribute('aria-checked', String(c === initial));
    });
  }

  /* ---------- Ripple effect on buttons ---------- */
  function bindRipple() {
    var reduce = window.matchMedia && matchMedia('(prefers-reduced-motion: reduce)').matches;
    if (reduce) return;  // respect accessibility preference
    document.addEventListener('click', function (e) {
      var btn = e.target.closest('.btn');
      if (!btn) return;
      var rect = btn.getBoundingClientRect();
      var size = Math.max(rect.width, rect.height) * 2;
      var ripple = document.createElement('span');
      ripple.className = 'ripple';
      ripple.style.width = ripple.style.height = size + 'px';
      ripple.style.left = (e.clientX - rect.left - size / 2) + 'px';
      ripple.style.top = (e.clientY - rect.top - size / 2) + 'px';
      btn.appendChild(ripple);
      ripple.addEventListener('animationend', function () { ripple.remove(); });
    });
  }
})();
