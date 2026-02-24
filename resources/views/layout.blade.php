<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'نظام الجمعيات')</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; padding: 20px; transition: background 0.3s, color 0.3s; }
        .container { max-width: 1200px; margin: 0 auto; }
        nav { padding: 15px; margin-bottom: 30px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; }
        nav .nav-links { display: flex; flex-wrap: wrap; align-items: center; }
        nav a { text-decoration: none; margin: 0 15px; font-weight: bold; }
        h1, h2, h3 { margin: 20px 0; font-weight: 900; }
        .card { padding: 20px; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { padding: 12px; text-align: right; }
        th { font-weight: 900; }
        .btn { display: inline-block; padding: 12px 24px; text-decoration: none; 
               cursor: pointer; font-weight: 900; margin: 5px; transition: all 0.3s; }
        input, select, textarea { width: 100%; padding: 12px; margin: 10px 0; font-size: 16px; }
        label { display: block; font-weight: 900; margin-top: 15px; }
        .alert { padding: 15px; margin: 20px 0; font-weight: bold; }
        .stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin: 20px 0; }
        .stat-box { padding: 20px; text-align: center; }
        .stat-box h3 { font-size: 32px; }
        .badge { display: inline-block; padding: 5px 10px; font-weight: bold; margin: 2px; }
        
        /* Theme Toggle Button */
        .theme-toggle { padding: 8px 16px; border-radius: 20px; font-weight: bold; cursor: pointer; 
                        transition: all 0.3s; margin-left: 20px; }
        
        /* BRUTAL THEME (Default) */
        body.brutal { background: #f5f5f5; color: #000; }
        body.brutal nav { background: #000; color: #fff; border: 4px solid #000; }
        body.brutal nav a { color: #fff; }
        body.brutal nav a:hover { color: #ff0; }
        body.brutal .card { background: #fff; border: 4px solid #000; }
        body.brutal table, body.brutal th, body.brutal td { border: 3px solid #000; }
        body.brutal th { background: #000; color: #fff; }
        body.brutal .btn { background: #000; color: #fff; border: 3px solid #000; }
        body.brutal .btn:hover { background: #fff; color: #000; }
        body.brutal .btn-danger { background: #f00; border-color: #f00; }
        body.brutal .btn-danger:hover { background: #fff; color: #f00; }
        body.brutal .btn-success { background: #0f0; color: #000; border-color: #0f0; }
        body.brutal input, body.brutal select, body.brutal textarea { border: 3px solid #000; background: #fff; }
        body.brutal .alert { border: 4px solid #000; }
        body.brutal .alert-success { background: #0f0; }
        body.brutal .alert-error { background: #f00; color: #fff; }
        body.brutal .stat-box { background: #fff; border: 4px solid #000; }
        body.brutal .badge { border: 2px solid #000; }
        body.brutal .badge-active { background: #0f0; }
        body.brutal .badge-inactive { background: #ccc; }
        body.brutal .badge-pending { background: #ff0; }
        body.brutal .badge-completed { background: #0f0; }
        body.brutal .badge-cancelled { background: #f00; color: #fff; }
        body.brutal .theme-toggle { background: #000; color: #fff; border: 3px solid #000; }
        body.brutal .theme-toggle:hover { background: #fff; color: #000; }
        
        /* DARK THEME */
        body.dark { background: #1a1a1a; color: #e0e0e0; }
        body.dark nav { background: #2d2d2d; color: #e0e0e0; border: 3px solid #444; }
        body.dark nav a { color: #e0e0e0; }
        body.dark nav a:hover { color: #4af; }
        body.dark .card { background: #2d2d2d; border: 3px solid #444; }
        body.dark table, body.dark th, body.dark td { border: 2px solid #444; }
        body.dark th { background: #383838; color: #e0e0e0; }
        body.dark .btn { background: #4af; color: #000; border: 2px solid #4af; }
        body.dark .btn:hover { background: #6cf; color: #000; }
        body.dark .btn-danger { background: #f44; border-color: #f44; color: #fff; }
        body.dark .btn-danger:hover { background: #f66; }
        body.dark .btn-success { background: #4f4; color: #000; border-color: #4f4; }
        body.dark .btn-success:hover { background: #6f6; }
        body.dark input, body.dark select, body.dark textarea { border: 2px solid #444; background: #2d2d2d; color: #e0e0e0; }
        body.dark .alert { border: 3px solid #444; }
        body.dark .alert-success { background: #2d5; color: #fff; }
        body.dark .alert-error { background: #d22; color: #fff; }
        body.dark .stat-box { background: #2d2d2d; border: 3px solid #444; color: #e0e0e0; }
        body.dark .stat-box h3 { color: #4af; }
        body.dark .badge { border: 2px solid #444; background: #383838; }
        body.dark .badge-active { background: #2d5; color: #fff; }
        body.dark .badge-inactive { background: #555; }
        body.dark .badge-pending { background: #fa0; color: #000; }
        body.dark .badge-completed { background: #2d5; color: #fff; }
        body.dark .badge-cancelled { background: #d22; color: #fff; }
        body.dark .theme-toggle { background: #4af; color: #000; border: 2px solid #4af; }
        body.dark .theme-toggle:hover { background: #6cf; }
        
        /* SOFT THEME */
        body.soft { background: #f9f7f4; color: #5a5a5a; }
        body.soft nav { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #fff; border: none; border-radius: 15px; box-shadow: 0 8px 20px rgba(0,0,0,0.15); }
        body.soft nav a { color: #fff; }
        body.soft nav a:hover { color: #ffd700; }
        body.soft .card { background: #fff; border: none; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        body.soft table, body.soft th, body.soft td { border: 1px solid #e0e0e0; }
        body.soft th { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #fff; }
        body.soft .btn { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #fff; border: none; border-radius: 8px; box-shadow: 0 4px 10px rgba(102, 126, 234, 0.4); }
        body.soft .btn:hover { transform: translateY(-2px); box-shadow: 0 6px 15px rgba(102, 126, 234, 0.6); }
        body.soft .btn-danger { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); box-shadow: 0 4px 10px rgba(245, 87, 108, 0.4); }
        body.soft .btn-danger:hover { box-shadow: 0 6px 15px rgba(245, 87, 108, 0.6); }
        body.soft .btn-success { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: #fff; box-shadow: 0 4px 10px rgba(79, 172, 254, 0.4); }
        body.soft .btn-success:hover { box-shadow: 0 6px 15px rgba(79, 172, 254, 0.6); }
        body.soft input, body.soft select, body.soft textarea { border: 2px solid #e0e0e0; border-radius: 8px; background: #fff; }
        body.soft input:focus, body.soft select:focus, body.soft textarea:focus { outline: none; border-color: #667eea; }
        body.soft .alert { border: none; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        body.soft .alert-success { background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%); color: #2d5; }
        body.soft .alert-error { background: linear-gradient(135deg, #fbc2eb 0%, #a6c1ee 100%); color: #d22; }
        body.soft .stat-box { background: #fff; border: none; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        body.soft .stat-box h3 { color: #667eea; }
        body.soft .badge { border: none; border-radius: 12px; }
        body.soft .badge-active { background: #4facfe; color: #fff; }
        body.soft .badge-inactive { background: #e0e0e0; }
        body.soft .badge-pending { background: #ffd700; color: #5a5a5a; }
        body.soft .badge-completed { background: #00f2fe; color: #fff; }
        body.soft .badge-cancelled { background: #f5576c; color: #fff; }
        body.soft .theme-toggle { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #fff; border: none; box-shadow: 0 4px 10px rgba(102, 126, 234, 0.4); }
        body.soft .theme-toggle:hover { transform: translateY(-2px); box-shadow: 0 6px 15px rgba(102, 126, 234, 0.6); }
        
        /* MINT GREEN THEME */
        body.mint { background: #e8f5f1; color: #2d5f54; }
        body.mint nav { background: linear-gradient(135deg, #48c6a0 0%, #2ecc71 100%); color: #fff; border: none; border-radius: 12px; box-shadow: 0 6px 18px rgba(72, 198, 160, 0.3); }
        body.mint nav a { color: #fff; }
        body.mint nav a:hover { color: #f0fff4; text-shadow: 0 0 10px rgba(255,255,255,0.5); }
        body.mint .card { background: #fff; border: 3px solid #48c6a0; border-radius: 12px; box-shadow: 0 4px 12px rgba(72, 198, 160, 0.15); }
        body.mint table, body.mint th, body.mint td { border: 2px solid #a8e6cf; }
        body.mint th { background: linear-gradient(135deg, #48c6a0 0%, #2ecc71 100%); color: #fff; }
        body.mint .btn { background: #48c6a0; color: #fff; border: 2px solid #48c6a0; border-radius: 8px; box-shadow: 0 3px 8px rgba(72, 198, 160, 0.3); }
        body.mint .btn:hover { background: #3ab089; transform: translateY(-1px); box-shadow: 0 5px 12px rgba(72, 198, 160, 0.4); }
        body.mint .btn-danger { background: #e74c3c; border-color: #e74c3c; box-shadow: 0 3px 8px rgba(231, 76, 60, 0.3); }
        body.mint .btn-danger:hover { background: #c0392b; box-shadow: 0 5px 12px rgba(231, 76, 60, 0.4); }
        body.mint .btn-success { background: #27ae60; border-color: #27ae60; box-shadow: 0 3px 8px rgba(39, 174, 96, 0.3); }
        body.mint .btn-success:hover { background: #229954; box-shadow: 0 5px 12px rgba(39, 174, 96, 0.4); }
        body.mint input, body.mint select, body.mint textarea { border: 2px solid #a8e6cf; border-radius: 8px; background: #f0fff4; color: #2d5f54; }
        body.mint input:focus, body.mint select:focus, body.mint textarea:focus { outline: none; border-color: #48c6a0; box-shadow: 0 0 0 3px rgba(72, 198, 160, 0.2); }
        body.mint .alert { border: 2px solid #a8e6cf; border-radius: 10px; box-shadow: 0 3px 10px rgba(72, 198, 160, 0.15); }
        body.mint .alert-success { background: linear-gradient(135deg, #d5f4e6 0%, #c8f7dc 100%); color: #27ae60; border-color: #27ae60; }
        body.mint .alert-error { background: linear-gradient(135deg, #fadbd8 0%, #f5b7b1 100%); color: #c0392b; border-color: #e74c3c; }
        body.mint .stat-box { background: #fff; border: 3px solid #a8e6cf; border-radius: 12px; box-shadow: 0 4px 12px rgba(72, 198, 160, 0.15); }
        body.mint .stat-box h3 { color: #48c6a0; }
        body.mint .badge { border: 2px solid #a8e6cf; border-radius: 10px; background: #f0fff4; }
        body.mint .badge-active { background: #27ae60; color: #fff; border-color: #27ae60; }
        body.mint .badge-inactive { background: #d5f4e6; color: #2d5f54; }
        body.mint .badge-pending { background: #f9e79f; color: #7d6608; border-color: #f9e79f; }
        body.mint .badge-completed { background: #48c6a0; color: #fff; border-color: #48c6a0; }
        body.mint .badge-cancelled { background: #e74c3c; color: #fff; border-color: #e74c3c; }
        body.mint .theme-toggle { background: linear-gradient(135deg, #48c6a0 0%, #2ecc71 100%); color: #fff; border: none; border-radius: 20px; box-shadow: 0 4px 10px rgba(72, 198, 160, 0.4); }
        body.mint .theme-toggle:hover { transform: translateY(-2px); box-shadow: 0 6px 15px rgba(72, 198, 160, 0.5); }
        body.mint h1, body.mint h2, body.mint h3 { color: #2d5f54; }
        body.mint a { color: #48c6a0; }
        body.mint a:hover { color: #3ab089; }
    </style>
</head>
<body>
    <div class="container">
        <nav>
            <div class="nav-links">
                <a href="{{ route('dashboard') }}">الرئيسية</a>
                <a href="{{ route('clients.index') }}">العملاء</a>
                <a href="{{ route('associations.index') }}">الجمعيات</a>
                <a href="{{ route('safes.index') }}">الصناديق</a>
                <a href="{{ route('loans.index') }}">السلف</a>
                <a href="{{ route('deposits.index') }}">الأمانات</a>
                <a href="{{ route('debts.index') }}">الديون</a>
                <a href="{{ route('commissions.index') }}">العمولات</a>
                <a href="{{ route('penalties.index') }}">الغرامات</a>
                <a href="{{ route('payments.index') }}">المدفوعات</a>
                <a href="{{ route('receipts.index') }}">المقبوضات</a>
                <a href="{{ route('reports.index') }}">التقارير</a>
                @auth
                    <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn" style="margin: 0; padding: 8px 16px; font-size: 14px;">تسجيل الخروج</button>
                    </form>
                @endauth
            </div>
            <button class="theme-toggle" onclick="toggleTheme()">🎨 تغيير الثيم</button>
        </nav>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-error">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        @yield('content')
    </div>

    <script>
        // Load saved theme or default to 'brutal'
        let currentTheme = localStorage.getItem('theme') || 'brutal';
        document.body.className = currentTheme;

        function toggleTheme() {
            const themes = ['brutal', 'dark', 'soft', 'mint'];
            const currentIndex = themes.indexOf(currentTheme);
            const nextIndex = (currentIndex + 1) % themes.length;
            currentTheme = themes[nextIndex];
            
            document.body.className = currentTheme;
            localStorage.setItem('theme', currentTheme);
            
            // Show theme name
            const themeNames = {
                'brutal': 'بروتال 💪',
                'dark': 'داكن 🌙',
                'soft': 'سوفت 🌸',
                'mint': 'نعناع 🌿'
            };
            
            // Optional: show a toast notification
            const toast = document.createElement('div');
            toast.style.cssText = 'position: fixed; top: 20px; left: 50%; transform: translateX(-50%); padding: 15px 30px; background: rgba(0,0,0,0.8); color: #fff; border-radius: 10px; font-weight: bold; z-index: 9999; animation: fadeInOut 2s;';
            toast.textContent = 'الثيم: ' + themeNames[currentTheme];
            document.body.appendChild(toast);
            
            setTimeout(() => toast.remove(), 2000);
        }
        
        // Add fade animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes fadeInOut {
                0% { opacity: 0; transform: translateX(-50%) translateY(-20px); }
                20% { opacity: 1; transform: translateX(-50%) translateY(0); }
                80% { opacity: 1; transform: translateX(-50%) translateY(0); }
                100% { opacity: 0; transform: translateX(-50%) translateY(-20px); }
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>

