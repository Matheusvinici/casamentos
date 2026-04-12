<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-1 text-primary">
                                <i class="fas fa-camera me-2"></i>
                                Cadastrar Biometria Facial
                            </h4>
                            <p class="text-muted mb-0">Capture a foto dos alunos para a validação de presença</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <label class="form-label fw-bold text-primary">Turma</label>
                    <select wire:model.live="turma_id" class="form-select">
                        <option value="">Selecione uma Turma</option>
                        @foreach($turmas as $turma)
                            <option value="{{ $turma->id }}">{{ $turma->nome }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    @if(count($alunos) > 0)
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-center" style="width: 5%">#</th>
                                <th style="width: 45%">Aluno</th>
                                <th class="text-center" style="width: 20%">Status Biometria</th>
                                <th class="text-center" style="width: 30%">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($alunos as $aluno)
                                @php
                                    $fotoAtiva = $aluno->fotos->where('ativo', true)->first();
                                @endphp
                                <tr>
                                    <td class="text-center text-muted">{{ $loop->iteration }}</td>
                                    <td>
                                        <div class="fw-bold">{{ $aluno->nome }}</div>
                                        <small class="text-muted">Matrícula: {{ $aluno->matricula_id ?? 'N/A' }}</small>
                                    </td>
                                    <td class="text-center">
                                        @if($fotoAtiva)
                                            <span class="badge bg-success">
                                                <i class="fas fa-check me-1"></i> Cadastrada
                                            </span>
                                        @else
                                            <span class="badge bg-danger">
                                                <i class="fas fa-times me-1"></i> Ausente
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($fotoAtiva)
                                            <button wire:click="removerFoto({{ $fotoAtiva->id }})"
                                                class="btn btn-sm btn-outline-danger mb-0">
                                                <i class="fas fa-trash me-1"></i> Remover
                                            </button>
                                        @else
                                            <div class="d-flex justify-content-center gap-2">
                                                <button onclick="abrirCamera({{ $aluno->id }}, '{{ addslashes($aluno->nome) }}')"
                                                    class="btn btn-sm btn-primary mb-0" title="Capturar com a Câmera">
                                                    <i class="fas fa-camera"></i> Capturar
                                                </button>
                                                <button onclick="abrirModalUpload({{ $aluno->id }}, '{{ addslashes($aluno->nome) }}')"
                                                    class="btn btn-sm btn-secondary mb-0" title="Fazer Upload de Arquivo">
                                                    <i class="fas fa-upload"></i> Upload
                                                </button>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @elseif($turma_id)
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <i class="fas fa-users-slash fa-3x text-muted mb-3"></i>
                <h5>Nenhum aluno encontrado</h5>
                <p class="text-muted">Não há alunos nesta turma.</p>
            </div>
        </div>
    @endif

    <!-- Modal Camera -->
    <div class="modal fade" id="cameraModal" tabindex="-1" role="dialog" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-light">
                    <h5 class="modal-title text-primary" id="cameraModalLabel">
                        <i class="fas fa-camera retro me-2"></i>Capturar Foto - <span id="alunoNomeCamera"
                            class="fw-bold text-dark"></span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center p-4">
                    <div id="loadingModels" class="py-5" style="display: none;">
                        <i class="fas fa-spinner fa-spin fa-3x text-primary mb-3"></i>
                        <h6 class="text-muted">Carregando modelos de Inteligência Artificial...</h6>
                        <small>Isso pode levar alguns segundos no primeiro acesso.</small>
                    </div>
                    <div id="cameraContainer"
                        style="position: relative; display: inline-block; box-shadow: 0 4px 15px rgba(0,0,0,0.1); border-radius: 12px; overflow: hidden; background: #000;">
                        <video id="videoElement" autoplay playsinline
                            style="max-width: 100%; max-height: 480px;"></video>
                        <canvas id="overlayCanvas" style="position: absolute; top: 0; left: 0;"></canvas>
                    </div>
                    <div class="mt-4 p-3 bg-light rounded" id="captureStatusDiv">
                        <div id="captureStatus" class="fw-bold fs-5 text-muted">Aguardando carregamento da câmera...
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                        onclick="fecharCamera()">Cancelar</button>
                    <button type="button" class="btn btn-primary px-4" id="btnCapturar" onclick="capturarFace()"
                        disabled>
                        <i class="fas fa-save me-2"></i>Salvar Captura
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Upload -->
    <div class="modal fade" id="uploadModal" tabindex="-1" role="dialog" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-light">
                    <h5 class="modal-title text-primary" id="uploadModalLabel">
                        <i class="fas fa-upload me-2"></i>Upload de Foto - <span id="alunoNomeUpload"
                            class="fw-bold text-dark"></span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center p-4">
                    <input type="file" id="fotoUploadInput" accept="image/*" class="form-control mb-3" onchange="previewUpload(event)">
                    
                    <div id="loadingModelsUpload" class="py-5" style="display: none;">
                        <i class="fas fa-spinner fa-spin fa-3x text-primary mb-3"></i>
                        <h6 class="text-muted">Processando imagem e carregando IA...</h6>
                    </div>

                    <div id="uploadPreviewContainer"
                        style="position: relative; display: none; margin: 0 auto; box-shadow: 0 4px 15px rgba(0,0,0,0.1); border-radius: 12px; overflow: hidden; background: #000;">
                        <img id="uploadPreviewImage" style="max-width: 100%; max-height: 480px; display: block;" />
                        <canvas id="uploadOverlayCanvas" style="position: absolute; top: 0; left: 0; pointer-events: none;"></canvas>
                    </div>

                    <div class="mt-4 p-3 bg-light rounded" id="uploadStatusDiv" style="display: none;">
                        <div id="uploadStatus" class="fw-bold fs-5 text-muted"></div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary px-4" id="btnSalvarUpload" onclick="salvarUpload()" disabled>
                        <i class="fas fa-save me-2"></i>Salvar Foto
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <!-- Face API -->
    <script src="{{ asset('js/face-api.min.js') }}"></script>
    <script>
        let videoStream = null;
        let currentAlunoId = null;
        let modelsLoaded = false;
        let faceDetectionInterval;

        async function carregarModelos() {
            if (modelsLoaded) return;
            document.getElementById('loadingModels').style.display = 'block';
            document.getElementById('cameraContainer').style.display = 'none';
            document.getElementById('captureStatusDiv').style.display = 'none';

            try {
                await Promise.all([
                    faceapi.nets.ssdMobilenetv1.loadFromUri('/models'),
                    faceapi.nets.faceLandmark68Net.loadFromUri('/models'),
                    faceapi.nets.faceRecognitionNet.loadFromUri('/models')
                ]);
                modelsLoaded = true;
                document.getElementById('loadingModels').style.display = 'none';
                document.getElementById('cameraContainer').style.display = 'inline-block';
                document.getElementById('captureStatusDiv').style.display = 'block';
            } catch (e) {
                console.error(e);
                alert('Erro ao carregar modelos de IA');
            }
        }

        async function abrirCamera(alunoId, alunoNome) {
            currentAlunoId = alunoId;
            document.getElementById('alunoNomeCamera').innerText = alunoNome;
            document.getElementById('captureStatus').innerHTML = '<span class="text-warning"><i class="fas fa-spinner fa-spin"></i> Inicializando câmera...</span>';
            document.getElementById('btnCapturar').disabled = true;

            var modal = new bootstrap.Modal(document.getElementById('cameraModal'));
            modal.show();

            await carregarModelos();
            iniciarVideo();
        }

        async function iniciarVideo() {
            const video = document.getElementById('videoElement');
            try {
                videoStream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' } });
                video.srcObject = videoStream;

                video.addEventListener('play', () => {
                    const canvas = document.getElementById('overlayCanvas');
                    const displaySize = { width: video.videoWidth, height: video.videoHeight };
                    faceapi.matchDimensions(canvas, displaySize);

                    faceDetectionInterval = setInterval(async () => {
                        if (video.paused || video.ended) return;

                        const detection = await faceapi.detectSingleFace(video).withFaceLandmarks().withFaceDescriptor();

                        const ctx = canvas.getContext('2d');
                        ctx.clearRect(0, 0, canvas.width, canvas.height);

                        if (detection) {
                            const resizedDetections = faceapi.resizeResults(detection, displaySize);
                            faceapi.draw.drawDetections(canvas, resizedDetections);

                            document.getElementById('captureStatus').innerHTML = '<span class="text-success"><i class="fas fa-check-circle fs-4 me-2"></i> Rosto detectado com ótima qualidade!</span>';
                            document.getElementById('btnCapturar').disabled = false;
                            window.currentDescriptor = Array.from(detection.descriptor);
                            window.currentDetection = detection;
                        } else {
                            document.getElementById('captureStatus').innerHTML = '<span class="text-warning"><i class="fas fa-exclamation-triangle fs-4 me-2"></i> Posicione o rosto no centro da câmera</span>';
                            document.getElementById('btnCapturar').disabled = true;
                            window.currentDescriptor = null;
                        }
                    }, 400);
                });
            } catch (e) {
                console.error(e);
                document.getElementById('captureStatus').innerHTML = '<span class="text-danger"><i class="fas fa-camera-slash"></i> Erro ao acessar câmera. Verifique as permissões.</span>';
            }
        }

        function fecharCamera() {
            if (videoStream) {
                videoStream.getTracks().forEach(track => track.stop());
            }
            if (faceDetectionInterval) {
                clearInterval(faceDetectionInterval);
            }
        }

        document.getElementById('cameraModal').addEventListener('hidden.bs.modal', function () {
            fecharCamera();
        });

        function capturarFace() {
            if (!window.currentDescriptor) return;

            const video = document.getElementById('videoElement');
            const canvas = document.createElement('canvas');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            canvas.getContext('2d').drawImage(video, 0, 0);
            const fotoBase64 = canvas.toDataURL('image/jpeg', 0.8);

            const descriptorJson = JSON.stringify(window.currentDescriptor);

            document.getElementById('btnCapturar').innerHTML = '<i class="fas fa-spinner fa-spin"></i> Salvando...';
            document.getElementById('btnCapturar').disabled = true;

            fecharCamera();

            setTimeout(() => {
                bootstrap.Modal.getInstance(document.getElementById('cameraModal')).hide();
                document.getElementById('btnCapturar').innerHTML = '<i class="fas fa-save me-2"></i>Salvar Captura';
                @this.salvarFoto(currentAlunoId, fotoBase64, descriptorJson);
            }, 500);
        }

        // Upload Functions
        let uploadAlunoId = null;
        let currentUploadDescriptor = null;
        let currentUploadBase64 = null;

        function abrirModalUpload(alunoId, alunoNome) {
            uploadAlunoId = alunoId;
            document.getElementById('alunoNomeUpload').innerText = alunoNome;
            document.getElementById('fotoUploadInput').value = '';
            document.getElementById('uploadPreviewContainer').style.display = 'none';
            document.getElementById('uploadStatusDiv').style.display = 'none';
            document.getElementById('btnSalvarUpload').disabled = true;
            currentUploadDescriptor = null;
            currentUploadBase64 = null;

            var modal = new bootstrap.Modal(document.getElementById('uploadModal'));
            modal.show();
            
            carregarModelos();
        }

        async function previewUpload(event) {
            const file = event.target.files[0];
            if (!file) return;

            document.getElementById('uploadPreviewContainer').style.display = 'none';
            document.getElementById('uploadStatusDiv').style.display = 'none';
            document.getElementById('btnSalvarUpload').disabled = true;
            document.getElementById('loadingModelsUpload').style.display = 'block';

            const reader = new FileReader();
            reader.onload = async (e) => {
                const base64 = e.target.result;
                currentUploadBase64 = base64;
                
                const imgElement = document.getElementById('uploadPreviewImage');
                imgElement.src = base64;

                imgElement.onload = async () => {
                    await carregarModelos();

                    document.getElementById('loadingModelsUpload').style.display = 'none';
                    document.getElementById('uploadPreviewContainer').style.display = 'inline-block';
                    document.getElementById('uploadStatusDiv').style.display = 'block';
                    
                    document.getElementById('uploadStatus').innerHTML = '<span class="text-warning"><i class="fas fa-spinner fa-spin me-2"></i> Analisando face...</span>';

                    const canvas = document.getElementById('uploadOverlayCanvas');
                    const displaySize = { width: imgElement.width, height: imgElement.height };
                    faceapi.matchDimensions(canvas, displaySize);

                    const detection = await faceapi.detectSingleFace(imgElement).withFaceLandmarks().withFaceDescriptor();

                    const ctx = canvas.getContext('2d');
                    ctx.clearRect(0, 0, canvas.width, canvas.height);

                    if (detection) {
                        const resizedDetections = faceapi.resizeResults(detection, displaySize);
                        faceapi.draw.drawDetections(canvas, resizedDetections);
                        
                        document.getElementById('uploadStatus').innerHTML = '<span class="text-success"><i class="fas fa-check-circle me-2"></i> Rosto detectado com ótima qualidade!</span>';
                        document.getElementById('btnSalvarUpload').disabled = false;
                        currentUploadDescriptor = Array.from(detection.descriptor);
                    } else {
                        document.getElementById('uploadStatus').innerHTML = '<span class="text-danger"><i class="fas fa-exclamation-triangle me-2"></i> Nenhum rosto detectado ou qualidade baixa.</span>';
                        document.getElementById('btnSalvarUpload').disabled = true;
                        currentUploadDescriptor = null;
                    }
                };
            };
            reader.readAsDataURL(file);
        }

        function salvarUpload() {
            if (!currentUploadDescriptor || !currentUploadBase64) return;

            const descriptorJson = JSON.stringify(currentUploadDescriptor);

            document.getElementById('btnSalvarUpload').innerHTML = '<i class="fas fa-spinner fa-spin"></i> Salvando...';
            document.getElementById('btnSalvarUpload').disabled = true;

            setTimeout(() => {
                bootstrap.Modal.getInstance(document.getElementById('uploadModal')).hide();
                document.getElementById('btnSalvarUpload').innerHTML = '<i class="fas fa-save me-2"></i>Salvar Foto';
                @this.salvarFoto(uploadAlunoId, currentUploadBase64, descriptorJson);
            }, 500);
        }
    </script>
@endpush