@extends('layouts.app')

@section('content')
    <link rel="stylesheet" href="https://unpkg.com/wavesurfer.js/dist/style.css" />
    <div class="d-flex justify-content-center align-items-center py-5 text-white px-3">
        <div class="card quote-box bg-secondary border-0 shadow-lg w-100 my-5" style="max-width: 600px;">
            <div class="card-body text-center py-5">

                <h2 class="card-title text-info mb-4">Your Daily Motivation</h2>

                <p class="fs-5 fst-italic text-light">"{{ $quote }}"</p>

                <div class="mt-4">
                    <div id="waveform" class="my-3 rounded" style="height: 100px;"></div>
                    <div class="text-center">
                        <button class="btn btn-outline-light" id="playPause">Play / Pause</button>
                    </div>
                </div>

                <div class="mt-3 text-white small">
                    Generated just for you ✨
                </div>
            </div>
        </div>
    </div>
    <!-- Before closing </body> -->
    <script src="https://unpkg.com/wavesurfer.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const wavesurfer = WaveSurfer.create({
                container: '#waveform',
                waveColor: '#0dcaf0',
                progressColor: '#198754',
                height: 80,
                responsive: true,
            });

            wavesurfer.load('{{ $audioUrl }}');

            document.getElementById('playPause').addEventListener('click', () => {
                wavesurfer.playPause();
            });
        });

    </script>
@endsection