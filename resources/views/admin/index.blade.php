@extends('adminlte::page')

@section('title', 'Municipalidad JLO')

@section('content_header')
   
@stop

<link rel="stylesheet" href="{{ asset('css/adminlte.css') }}">


@section('content')
    
    <div class="welcome-container">
        <div class="welcome-content">

            <h2 class="welcome-title">¡Bienvenido al Panel de Administración!</h2>
            <p class="welcome-subtitle">Gestiona eficientemente los recursos humanos de la Municipalidad JLO</p>
        </div>
    </div>

  
    <div class="project-section">
        <div class="project-header">
            <div class="section-indicator"></div>
            <h3 class="section-title">Información del Proyecto</h3>
        </div>

        <div class="project-content">
            <div class="project-main">
                <h4 class="project-name">Sistema de Gestión Municipal JLO</h4>
                <p class="project-description">
                    Este sistema ha sido desarrollado como parte del curso de
                    <span class="highlight">"Tópicos Avanzados en Desarrollo de Software"</span>
                </p>
            </div>

            <div class="project-details">
                <div class="detail-card university-card">
                    <div class="card-icon">🎓</div>
                    <div class="card-content">
                        <h5 class="card-title">USAT</h5>
                        <p class="card-subtitle"> <br> <br> Universidad Católica Santo Toribio de Mogrovejo</p>
                    </div>
                </div>

                <div class="detail-card team-card">
                    <div class="card-icon">👥</div>
                    <div class="card-content">
                        <h5 class="card-title">Equipo de desarrollo</h5>
                        <p class="card-subtitle"> <br> <br> Diaz Cruz Eduardo <br> Santisteban Vargas Iris  <br> Vera Chavez Alex  <br> Yomona Parraguez Cinthya </p>
                    </div>
                </div>
            </div>

            
        </div>
    </div>
@stop

@section('css')
    <style>
        
        :root {
            --primary-green: #397044;
            --light-green: #f1f8ec;
            --dark-green: #047857;
            --accent-green: #397044;
            --text-primary: #1f2937;
            --text-secondary: #6b7280;
            --text-light: #9ca3af;
            --background-light: #f9fafb;
            --white: #ffffff;
            --border-light: #e5e7eb;
        }

        .content-wrapper {
            background-color: var(--background-light);
        }


        .content-header {
            padding: 15px 0.5rem;
            margin-bottom: -30px;
        }

        
        .content-header-minimal {
            background: var(--white);
            border-bottom: 1px solid var(--border-light);
            padding: 2rem 0;
            margin-bottom: 2rem;
        }

        .dashboard-title {
            font-size: 1.875rem;
            font-weight: 600;
            color: var(--text-primary);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .title-icon {
            font-size: 2rem;
        }

        .breadcrumb-minimal {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 0.5rem;
            font-size: 0.875rem;
        }

        .breadcrumb-link {
            color: var(--primary-green);
            text-decoration: none;
            font-weight: 500;
        }

        .breadcrumb-link:hover {
            color: var(--dark-green);
        }

        .breadcrumb-separator {
            color: var(--text-light);
        }

        .breadcrumb-current {
            color: var(--text-secondary);
        }

        .welcome-container {
            background: var(--white);
            border-radius: 16px;
            padding: 1rem 2rem;
            margin-bottom: 2rem;
            border: 1px solid var(--border-light);
            text-align: center;
                margin-top: 20px
        }

        .welcome-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            display: inline-block;
            animation: gentle-wave 3s ease-in-out infinite;
        }

        .welcome-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 0.75rem;
        }

        .welcome-subtitle {
            font-size: 1.125rem;
            color: var(--text-secondary);
            margin: 0;
            font-weight: 400;
        }

       
        .project-section {
            background: var(--white);
            border-radius: 16px;
            border: 1px solid var(--border-light);
            overflow: hidden;
        }

        .project-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1.5rem 2rem;
            background: var(--light-green);
            border-bottom: 1px solid var(--border-light);
        }

        .section-indicator {
            width: 4px;
            height: 24px;
            background: var(--primary-green);
            border-radius: 2px;
        }

        .section-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--dark-green);
            margin: 0;
        }

        .project-content {
            padding: 2rem;
        }

        .project-main {
            text-align: center;
            margin-bottom: 2rem;
        }

        .project-name {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 1rem;
        }

        .project-description {
            font-size: 1rem;
            color: var(--text-secondary);
            line-height: 1.6;
            margin: 0;
        }

        .highlight {
            color: var(--primary-green);
            font-weight: 600;
        }

        .project-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .detail-card {
            background: var(--background-light);
            border: 1px solid #397044;
            border-radius: 12px;
            padding: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            transition: all 0.3s ease;

        }

        .detail-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.1);
            border-color: var(--accent-green);
        }

        .card-icon {
            font-size: 2rem;
            flex-shrink: 0;
        }

        .card-title {
            font-size: 1.125rem;
            font-weight: 700;
            color: var(--primary-green);
            margin-bottom: 0.25rem;
        }

        .card-subtitle {
            font-size: 0.875rem;
            color: var(--text-secondary);
            margin: 0;
            line-height: 1.4;
            margin-left: 80px;
        }

        .project-footer {
            text-align: center;
            padding: 1.5rem;
            background: var(--light-green);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .footer-text {
            color: var(--text-secondary);
            font-size: 0.875rem;
        }

        .heart {
            font-size: 1rem;
            animation: gentle-pulse 2s ease-in-out infinite;
        }

        @keyframes gentle-wave {

            0%,
            100% {
                transform: rotate(0deg);
            }

            25% {
                transform: rotate(10deg);
            }

            75% {
                transform: rotate(-10deg);
            }
        }

        @keyframes gentle-pulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.1);
            }
        }

        @media (max-width: 768px) {
            .content-header-minimal {
                padding: 1.5rem 0;
            }

            .dashboard-title {
                font-size: 1.5rem;
            }

            .breadcrumb-minimal {
                justify-content: flex-start;
                margin-top: 0.5rem;
            }

            .welcome-container {
                padding: 2rem 1.5rem;
            }

            .welcome-title {
                font-size: 1.5rem;
            }

            .welcome-subtitle {
                font-size: 1rem;
            }

            .project-details {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .project-content {
                padding: 1.5rem;
            }

            .project-name {
                font-size: 1.5rem;
            }
        }
    </style>
@stop

@section('js')
    <script>
        console.log("Hi, I'm using the Laravel-AdminLTE package!");

        document.addEventListener('DOMContentLoaded', function() {
            const elements = document.querySelectorAll('.welcome-container, .project-section');

            elements.forEach((element, index) => {
                element.style.opacity = '0';
                element.style.transform = 'translateY(10px)';

                setTimeout(() => {
                    element.style.transition = 'all 0.6s cubic-bezier(0.4, 0, 0.2, 1)';
                    element.style.opacity = '1';
                    element.style.transform = 'translateY(0)';
                }, index * 150);
            });
        });
    </script>
@stop
