<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadwal Dan Slot</title>

    @vite(['resources/css/owner-jadwal-slot.css'])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
</head>
<body>

<div class="dashboard-layout">

    {{-- SIDEBAR --}}
    @include('owner.navbar')

    {{-- MAIN CONTENT --}}
    <main class="main-content">

        {{-- TOPBAR --}}
        <div class="topbar">
            <div class="search-box">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" placeholder="Search bookings, customers...">
            </div>

            <div class="topbar-right">
                <button class="notif-btn">
                    <i class="fa-solid fa-bell"></i>
                </button>

                <button class="notif-btn question">
                    <i class="fa-solid fa-circle-question"></i>
                </button>

                <div class="profile-box">
                    <div>
                        <h5>{{ auth()->user()->name }}</h5>
                        <p>Owner Profile</p>
                    </div>

                    <img src="https://i.pravatar.cc/100" alt="Profile">
                </div>
            </div>
        </div>

        <div class="welcome-section">
            <div>
                <h1>Jadwal & Slot</h1>
                <p>Atur jadwal operasional dan ketersediaan slot lapangan.</p>
            </div>

            <a href="{{ route('owner.tambahLapangan') }}" class="add-btn">
                <i class="fa-solid fa-plus"></i>
                Tambah Slot
            </a>
        </div>

        <div class="card-panel" style="display: flex; justify-content: space-between; align-items: center;">
            <div style="display: flex; gap: 1rem; flex: 1; max-width: 800px;">
                <select style="flex: 1; padding: 0.625rem; border: 1px solid #e5e7eb; border-radius: 0.75rem; background-color: #f9fafb;">
                    <option>Lapangan A</option>
                </select>
                <select style="flex: 1; padding: 0.625rem; border: 1px solid #e5e7eb; border-radius: 0.75rem; background-color: #f9fafb;">
                    <option>Jenis Olahraga</option>
                </select>
                <input type="date" value="2026-05-24" style="flex: 1; padding: 0.625rem; border: 1px solid #e5e7eb; border-radius: 0.75rem; background-color: #f9fafb;">
            </div>
            <button style="color: #e52d2d; border: 1px solid #fca5a5; background: none; padding: 0.625rem 1.25rem; border-radius: 0.75rem; font-size: 0.875rem; font-weight: 500; cursor: pointer; margin-left: 1rem;">
                <i class="fa-solid fa-rotate-right"></i> Reset Filter
            </button>
        </div>

        <div class="card-panel" style="padding: 0; overflow: hidden;">
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 1rem; border-bottom: 1px solid #f3f4f6;">
                <div style="display: flex; gap: 0.5rem; align-items: center;">
                    <button style="padding: 0.5rem; border: 1px solid #e5e7eb; background: #fff; border-radius: 0.5rem;"><i class="fa-solid fa-chevron-left"></i></button>
                    <span style="padding: 0.5rem 1rem; border: 1px solid #e5e7eb; background: #f9fafb; border-radius: 0.5rem; font-size: 0.875rem; font-weight: 500;">Lapangan A</span>
                    <button style="padding: 0.5rem; border: 1px solid #e5e7eb; background: #fff; border-radius: 0.5rem;"><i class="fa-solid fa-chevron-right"></i></button>
                </div>
                <span style="font-weight: 600; color: #4b5563; font-size: 0.875rem;">24 - 30 Mei 2026</span>
            </div>

            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.75rem;">
                    <thead>
                        <tr style="background-color: #f9fafb; border-bottom: 1px solid #f3f4f6; color: #6b7280;">
                            <th style="padding: 0.75rem 1rem; width: 100px;">WAKTU</th>
                            @for($d = 24; $d <= 30; $d++)
                                <th style="padding: 0.75rem 1rem; border-left: 1px solid #f3f4f6;">Min, {{ $d }} Mei</th>
                            @endfor
                        </tr>
                    </thead>
                    <tbody>
                        <tr style="height: 70px; border-bottom: 1px solid #f3f4f6;">
                            <td style="padding: 1rem; font-weight: 600; color: #9ca3af; background-color: #f9fafb;">08.00</td>
                            <td style="padding: 0.5rem; border-left: 1px solid #f3f4f6; vertical-align: top;">
                                <div class="slot-status status-tersedia">
                                    <div><strong>08.00 - 09.00</strong><br>Tersedia</div>
                                    <i class="fa-solid fa-ellipsis"></i>
                                </div>
                            </td>
                            @for($i=1; $i<=6; $i++) <td style="border-left: 1px solid #f3f4f6;"></td> @endfor
                        </tr>
                        <tr style="height: 70px; border-bottom: 1px solid #f3f4f6;">
                            <td style="padding: 1rem; font-weight: 600; color: #9ca3af; background-color: #f9fafb;">09.00</td>
                            <td style="border-left: 1px solid #f3f4f6;"></td>
                            <td style="padding: 0.5rem; border-left: 1px solid #f3f4f6; vertical-align: top;">
                                <div class="slot-status status-dibooking">
                                    <div><strong>09.00 - 10.00</strong><br>Telah dibooking</div>
                                    <i class="fa-solid fa-ellipsis"></i>
                                </div>
                            </td>
                            @for($i=2; $i<=6; $i++) <td style="border-left: 1px solid #f3f4f6;"></td> @endfor
                        </tr>
                        <tr style="height: 70px; border-bottom: 1px solid #f3f4f6;">
                            <td style="padding: 1rem; font-weight: 600; color: #9ca3af; background-color: #f9fafb;">10.00</td>
                            <td style="border-left: 1px solid #f3f4f6;"></td>
                            <td style="padding: 0.5rem; border-left: 1px solid #f3f4f6; vertical-align: top;">
                                <div class="slot-status status-perbaikan">
                                    <div><strong>10.00 - 11.00</strong><br>Perbaikan</div>
                                    <i class="fa-solid fa-ellipsis"></i>
                                </div>
                            </td>
                            @for($i=2; $i<=6; $i++) <td style="border-left: 1px solid #f3f4f6;"></td> @endfor
                        </tr>
                        <tr style="height: 70px;">
                            <td style="padding: 1rem; font-weight: 600; color: #9ca3af; background-color: #f9fafb;">11.00</td>
                            <td style="border-left: 1px solid #f3f4f6;"></td>
                            <td style="border-left: 1px solid #f3f4f6;"></td>
                            <td style="padding: 0.5rem; border-left: 1px solid #f3f4f6; vertical-align: top;">
                                <div class="slot-status status-tutup">
                                    <div><strong>11.00 - 12.00</strong><br>Tutup</div>
                                    <i class="fa-solid fa-ellipsis"></i>
                                </div>
                            </td>
                            <td style="padding: 0.5rem; border-left: 1px solid #f3f4f6;" rowspan="2">
                                <div class="locked-day">
                                    <i class="fa-solid fa-lock" style="font-size: 1.25rem; margin-bottom: 0.25rem;"></i>
                                    <span style="font-size: 0.65rem;">Tutup (Tanggal Merah)</span>
                                </div>
                            </td>
                            @for($i=4; $i<=6; $i++) <td style="border-left: 1px solid #f3f4f6;"></td> @endfor
                        </tr>
                        <tr style="height: 70px;">
                            <td style="padding: 1rem; font-weight: 600; color: #9ca3af; background-color: #f9fafb;">12.00</td>
                            @for($i=0; $i<=2; $i++) <td style="border-left: 1px solid #f3f4f6;"></td> @endfor
                            @for($i=4; $i<=6; $i++) <td style="border-left: 1px solid #f3f4f6;"></td> @endfor
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </main>

</div>

</body>
</html>

{{-- <!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadwal & Slot - SpieSport</title>
    
    @vite(['resources/css/owner-jadwal-slot.css'])
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="dashboard-container">

    @include('owner.navbar')

    <div class="main-content">
        
        <header class="topbar">
            <div class="search-box">
                <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: #9ca3af;"></i>
                <input type="text" placeholder="Search bookings, customers....">
            </div>

            <div style="display: flex; align-items: center; gap: 1.5rem;">
                <i class="fa-regular fa-bell" style="font-size: 1.25rem; color: #4b5563; cursor: pointer;"></i>
                <i class="fa-regular fa-circle-question" style="font-size: 1.25rem; color: #4b5563; cursor: pointer;"></i>
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <div style="text-align: right;">
                        <span style="display: block; font-size: 0.875rem; font-weight: 600;">Namtan</span>
                        <span style="display: block; font-size: 0.75rem; color: #9ca3af;">Owner Profile</span>
                    </div>
                    <img src="https://ui-avatars.com/api/?name=Namtan&background=fca5a5&color=fff" alt="Avatar" style="width: 40px; height: 40px; border-radius: 50%;">
                </div>
            </div>
        </header>

        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <div>
                <h1 style="font-size: 1.5rem; font-weight: 700; color: #1f2937; margin: 0;">Jadwal & Slot</h1>
                <p style="font-size: 0.875rem; color: #6b7280; margin: 0;">Atur jadwal operasional dan ketersediaan slot lapangan.</p>
            </div>
            <button class="btn-primary"><i class="fa-solid fa-plus"></i> Tambah Slot</button>
        </div>

        <div class="card-panel" style="display: flex; justify-content: space-between; align-items: center;">
            <div style="display: flex; gap: 1rem; flex: 1; max-width: 800px;">
                <select style="flex: 1; padding: 0.625rem; border: 1px solid #e5e7eb; border-radius: 0.75rem; background-color: #f9fafb;">
                    <option>Lapangan A</option>
                </select>
                <select style="flex: 1; padding: 0.625rem; border: 1px solid #e5e7eb; border-radius: 0.75rem; background-color: #f9fafb;">
                    <option>Jenis Olahraga</option>
                </select>
                <input type="date" value="2026-05-24" style="flex: 1; padding: 0.625rem; border: 1px solid #e5e7eb; border-radius: 0.75rem; background-color: #f9fafb;">
            </div>
            <button style="color: #e52d2d; border: 1px solid #fca5a5; background: none; padding: 0.625rem 1.25rem; border-radius: 0.75rem; font-size: 0.875rem; font-weight: 500; cursor: pointer; margin-left: 1rem;">
                <i class="fa-solid fa-rotate-right"></i> Reset Filter
            </button>
        </div>

        <div class="card-panel" style="padding: 0; overflow: hidden;">
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 1rem; border-bottom: 1px solid #f3f4f6;">
                <div style="display: flex; gap: 0.5rem; align-items: center;">
                    <button style="padding: 0.5rem; border: 1px solid #e5e7eb; background: #fff; border-radius: 0.5rem;"><i class="fa-solid fa-chevron-left"></i></button>
                    <span style="padding: 0.5rem 1rem; border: 1px solid #e5e7eb; background: #f9fafb; border-radius: 0.5rem; font-size: 0.875rem; font-weight: 500;">Lapangan A</span>
                    <button style="padding: 0.5rem; border: 1px solid #e5e7eb; background: #fff; border-radius: 0.5rem;"><i class="fa-solid fa-chevron-right"></i></button>
                </div>
                <span style="font-weight: 600; color: #4b5563; font-size: 0.875rem;">24 - 30 Mei 2026</span>
            </div>

            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.75rem;">
                    <thead>
                        <tr style="background-color: #f9fafb; border-bottom: 1px solid #f3f4f6; color: #6b7280;">
                            <th style="padding: 0.75rem 1rem; width: 100px;">WAKTU</th>
                            @for($d = 24; $d <= 30; $d++)
                                <th style="padding: 0.75rem 1rem; border-left: 1px solid #f3f4f6;">Min, {{ $d }} Mei</th>
                            @endfor
                        </tr>
                    </thead>
                    <tbody>
                        <tr style="height: 70px; border-bottom: 1px solid #f3f4f6;">
                            <td style="padding: 1rem; font-weight: 600; color: #9ca3af; background-color: #f9fafb;">08.00</td>
                            <td style="padding: 0.5rem; border-left: 1px solid #f3f4f6; vertical-align: top;">
                                <div class="slot-status status-tersedia">
                                    <div><strong>08.00 - 09.00</strong><br>Tersedia</div>
                                    <i class="fa-solid fa-ellipsis"></i>
                                </div>
                            </td>
                            @for($i=1; $i<=6; $i++) <td style="border-left: 1px solid #f3f4f6;"></td> @endfor
                        </tr>
                        <tr style="height: 70px; border-bottom: 1px solid #f3f4f6;">
                            <td style="padding: 1rem; font-weight: 600; color: #9ca3af; background-color: #f9fafb;">09.00</td>
                            <td style="border-left: 1px solid #f3f4f6;"></td>
                            <td style="padding: 0.5rem; border-left: 1px solid #f3f4f6; vertical-align: top;">
                                <div class="slot-status status-dibooking">
                                    <div><strong>09.00 - 10.00</strong><br>Telah dibooking</div>
                                    <i class="fa-solid fa-ellipsis"></i>
                                </div>
                            </td>
                            @for($i=2; $i<=6; $i++) <td style="border-left: 1px solid #f3f4f6;"></td> @endfor
                        </tr>
                        <tr style="height: 70px; border-bottom: 1px solid #f3f4f6;">
                            <td style="padding: 1rem; font-weight: 600; color: #9ca3af; background-color: #f9fafb;">10.00</td>
                            <td style="border-left: 1px solid #f3f4f6;"></td>
                            <td style="padding: 0.5rem; border-left: 1px solid #f3f4f6; vertical-align: top;">
                                <div class="slot-status status-perbaikan">
                                    <div><strong>10.00 - 11.00</strong><br>Perbaikan</div>
                                    <i class="fa-solid fa-ellipsis"></i>
                                </div>
                            </td>
                            @for($i=2; $i<=6; $i++) <td style="border-left: 1px solid #f3f4f6;"></td> @endfor
                        </tr>
                        <tr style="height: 70px;">
                            <td style="padding: 1rem; font-weight: 600; color: #9ca3af; background-color: #f9fafb;">11.00</td>
                            <td style="border-left: 1px solid #f3f4f6;"></td>
                            <td style="border-left: 1px solid #f3f4f6;"></td>
                            <td style="padding: 0.5rem; border-left: 1px solid #f3f4f6; vertical-align: top;">
                                <div class="slot-status status-tutup">
                                    <div><strong>11.00 - 12.00</strong><br>Tutup</div>
                                    <i class="fa-solid fa-ellipsis"></i>
                                </div>
                            </td>
                            <td style="padding: 0.5rem; border-left: 1px solid #f3f4f6;" rowspan="2">
                                <div class="locked-day">
                                    <i class="fa-solid fa-lock" style="font-size: 1.25rem; margin-bottom: 0.25rem;"></i>
                                    <span style="font-size: 0.65rem;">Tutup (Tanggal Merah)</span>
                                </div>
                            </td>
                            @for($i=4; $i<=6; $i++) <td style="border-left: 1px solid #f3f4f6;"></td> @endfor
                        </tr>
                        <tr style="height: 70px;">
                            <td style="padding: 1rem; font-weight: 600; color: #9ca3af; background-color: #f9fafb;">12.00</td>
                            @for($i=0; $i<=2; $i++) <td style="border-left: 1px solid #f3f4f6;"></td> @endfor
                            @for($i=4; $i<=6; $i++) <td style="border-left: 1px solid #f3f4f6;"></td> @endfor
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</body>
</html> --}}