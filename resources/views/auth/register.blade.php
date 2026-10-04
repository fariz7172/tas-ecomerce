<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar Akun Baru — Mikael On Shop</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,400;1,600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        rosepet: {
                            light: '#FFF9FA',
                            glow: '#FDF0F3',
                            soft: '#FCE7EC',
                            border: '#EBB4C4',
                            fresh: '#E87A90',
                            vibrant: '#D85A75',
                            deep: '#B76E79',
                            dark: '#4A2E35',
                            muted: '#8C6D75',
                        }
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        serif: ['"Playfair Display"', 'serif'],
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-rosepet-light text-rosepet-dark font-sans min-h-screen flex items-center justify-center p-6 antialiased">
    <div class="w-full max-w-md">
        <!-- Brand Header -->
        <div class="text-center mb-8">
            <a href="/" class="inline-flex items-center gap-3.5 group">
                <img src="{{ asset('assets/logo.png') }}" alt="Mikael on Shop" class="w-16 h-16 object-contain group-hover:scale-105 transition-transform drop-shadow-md">
                <div class="text-left">
                    <span class="font-serif font-black text-2xl tracking-[0.16em] uppercase text-rosepet-dark block leading-none">Mikael <span class="font-normal text-rosepet-fresh italic">on Shop</span></span>
                    <span class="text-[9px] uppercase tracking-widest text-rosepet-muted font-bold block mt-1">Luxury Member Atelier</span>
                </div>
            </a>
            <h2 class="font-serif text-2xl font-bold text-rosepet-dark mt-6">Buat Akun Member</h2>
            <p class="text-xs text-rosepet-muted mt-1">Daftar untuk menyimpan koleksi Wishlist dan belanja tas dengan mudah.</p>
        </div>

        <!-- Register Card -->
        <div class="bg-white rounded-3xl p-8 border-2 border-rosepet-border shadow-xl space-y-6">
            @if ($errors->any())
            <div class="p-3.5 rounded-2xl bg-rose-50 border border-rose-200 text-xs text-rose-700 font-semibold">
                {{ $errors->first() }}
            </div>
            @endif

            <form action="{{ route('register.post') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-rosepet-dark mb-1.5">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name') }}" required autofocus placeholder="Contoh: Fariz Herlambang" class="w-full bg-[#FFF9FA] rounded-xl px-4 py-3 text-xs text-rosepet-dark border-2 border-rosepet-soft outline-none focus:border-rosepet-fresh transition-colors font-medium">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-rosepet-dark mb-1.5">Email Akun</label>
                    <input type="email" name="email" value="{{ old('email') }}" required placeholder="nama@email.com" class="w-full bg-[#FFF9FA] rounded-xl px-4 py-3 text-xs text-rosepet-dark border-2 border-rosepet-soft outline-none focus:border-rosepet-fresh transition-colors font-medium">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-rosepet-dark mb-1.5">Kata Sandi (Min. 6 Karakter)</label>
                    <input type="password" name="password" required placeholder="••••••••" class="w-full bg-[#FFF9FA] rounded-xl px-4 py-3 text-xs text-rosepet-dark border-2 border-rosepet-soft outline-none focus:border-rosepet-fresh transition-colors font-medium">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-rosepet-dark mb-1.5">Konfirmasi Kata Sandi</label>
                    <input type="password" name="password_confirmation" required placeholder="••••••••" class="w-full bg-[#FFF9FA] rounded-xl px-4 py-3 text-xs text-rosepet-dark border-2 border-rosepet-soft outline-none focus:border-rosepet-fresh transition-colors font-medium">
                </div>

                <button type="submit" class="w-full py-3.5 px-6 rounded-2xl bg-gradient-to-r from-rosepet-fresh to-rosepet-vibrant hover:shadow-lg hover:shadow-rosepet-fresh/30 text-white font-bold text-xs uppercase tracking-wider transition-all mt-2">
                    Daftar Akun Sekarang
                </button>
            </form>

            <!-- Social Register Divider -->
            <div class="relative flex items-center justify-center my-4">
                <div class="border-t border-rosepet-soft w-full"></div>
                <span class="bg-white px-3 text-[10px] uppercase font-bold text-rosepet-muted tracking-wider">Atau Daftar Cepat</span>
                <div class="border-t border-rosepet-soft w-full"></div>
            </div>

            <!-- Social OAuth Buttons -->
            <div class="grid grid-cols-2 gap-3">
                <a href="{{ route('auth.google') }}" class="flex items-center justify-center gap-2 py-3 px-4 rounded-2xl border-2 border-rosepet-soft hover:border-rosepet-fresh bg-white hover:bg-rosepet-light text-rosepet-dark text-xs font-bold transition-all shadow-sm">
                    <svg class="w-4 h-4" viewBox="0 0 24 24"><path fill="#EA4335" d="M12 5c1.6 0 3 .6 4.1 1.7l3.1-3.1C17.3 1.8 14.8 1 12 1 7.5 1 3.7 3.6 1.9 7.3l3.7 2.9C6.5 7.3 9 5 12 5z"/><path fill="#4285F4" d="M23.5 12.3c0-.8-.1-1.6-.2-2.3H12v4.6h6.5c-.3 1.5-1.1 2.8-2.4 3.7l3.7 2.9c2.2-2 3.7-5 3.7-8.9z"/><path fill="#FBBC05" d="M5.6 14.8c-.2-.7-.4-1.5-.4-2.3s.2-1.6.4-2.3L1.9 7.3C.7 9.7 0 12.3 0 15.2s.7 5.5 1.9 7.9l3.7-2.9c-.2-.7-.4-1.5-.4-2.3z"/><path fill="#34A853" d="M12 23.5c3.2 0 6-1.1 8-3l-3.7-2.9c-1.1.7-2.5 1.2-4.3 1.2-3 0-5.5-2-6.4-4.8L1.9 16.9C3.7 20.6 7.5 23.5 12 23.5z"/></svg>
                    <span>Google</span>
                </a>
                <a href="{{ route('auth.facebook') }}" class="flex items-center justify-center gap-2 py-3 px-4 rounded-2xl border-2 border-rosepet-soft hover:border-blue-500 bg-white hover:bg-blue-50/30 text-rosepet-dark text-xs font-bold transition-all shadow-sm">
                    <svg class="w-4 h-4 text-[#1877F2]" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    <span>Facebook</span>
                </a>
            </div>

            <div class="pt-4 border-t border-rosepet-soft text-center text-xs text-rosepet-muted">
                Sudah memiliki akun? 
                <a href="{{ route('login') }}" class="font-bold text-rosepet-fresh hover:underline">Masuk di Sini</a>
            </div>
        </div>

        <div class="text-center mt-6">
            <a href="/" class="text-xs text-rosepet-muted hover:text-rosepet-fresh font-semibold transition-colors">
                ← Kembali ke Beranda Katalog
            </a>
        </div>
    </div>
</body>
</html>
