class VideoCall {
    constructor(config) {
        this.config = config;
        this.peerConnection = null;
        this.localStream = null;
        this.remoteStream = null;
        this.isInitiator = false;

        this.init();
    }

    async init() {
        try {
            // Get local media stream
            this.localStream = await navigator.mediaDevices.getUserMedia({
                video: this.config.videoConstraints,
                audio: true
            });

            // Display local video
            const localVideo = document.getElementById('local-video');
            localVideo.srcObject = this.localStream;

            // Initialize WebSocket connection
            this.initializeWebSocket();

            // Set up WebRTC peer connection
            this.setupPeerConnection();

            // Set up UI controls
            this.setupControls();
        } catch (error) {
            console.error('Error initializing video call:', error);
        }
    }

    initializeWebSocket() {
        // Connect to Laravel Reverb WebSocket
        window.Echo.private(`video-call.${this.config.user.id}`)
            .listen('video-call.signal', (data) => {
                this.handleSignalingData(data);
            });
    }

    setupPeerConnection() {
        const configuration = {
            iceServers: [
                ...this.config.stunServers.map(url => ({ urls: url })),
                ...this.config.turnServers
            ]
        };

        this.peerConnection = new RTCPeerConnection(configuration);

        // Add local stream tracks to peer connection
        this.localStream.getTracks().forEach(track => {
            this.peerConnection.addTrack(track, this.localStream);
        });

        // Handle incoming streams
        this.peerConnection.ontrack = (event) => {
            const remoteVideo = document.getElementById('remote-video');
            if (event.streams && event.streams[0]) {
                remoteVideo.srcObject = event.streams[0];
                this.remoteStream = event.streams[0];
            }
        };

        // Handle ICE candidates
        this.peerConnection.onicecandidate = (event) => {
            if (event.candidate) {
                this.sendSignal({
                    type: 'candidate',
                    candidate: event.candidate
                });
            }
        };
    }

    async handleSignalingData(data) {
        const signal = data.signal;

        try {
            if (signal.type === 'offer') {
                await this.peerConnection.setRemoteDescription(new RTCSessionDescription(signal));
                const answer = await this.peerConnection.createAnswer();
                await this.peerConnection.setLocalDescription(answer);
                this.sendSignal({
                    type: 'answer',
                    sdp: answer
                });
            } else if (signal.type === 'answer') {
                await this.peerConnection.setRemoteDescription(new RTCSessionDescription(signal));
            } else if (signal.type === 'candidate') {
                await this.peerConnection.addIceCandidate(new RTCIceCandidate(signal.candidate));
            }
        } catch (error) {
            console.error('Error handling signaling data:', error);
        }
    }

    async initiateCall() {
        try {
            const offer = await this.peerConnection.createOffer();
            await this.peerConnection.setLocalDescription(offer);
            this.sendSignal({
                type: 'offer',
                sdp: offer
            });
        } catch (error) {
            console.error('Error creating offer:', error);
        }
    }

    sendSignal(signal) {
        fetch('/video-call/signal', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                target_user_id: this.config.targetUserId,
                signal: signal
            })
        });
    }

    setupControls() {
        const toggleVideo = document.getElementById('toggle-video');
        const toggleAudio = document.getElementById('toggle-audio');
        const endCall = document.getElementById('end-call');

        toggleVideo.addEventListener('click', () => {
            const videoTrack = this.localStream.getVideoTracks()[0];
            videoTrack.enabled = !videoTrack.enabled;
            toggleVideo.querySelector('i').className = videoTrack.enabled ? 'fas fa-video' : 'fas fa-video-slash';
        });

        toggleAudio.addEventListener('click', () => {
            const audioTrack = this.localStream.getAudioTracks()[0];
            audioTrack.enabled = !audioTrack.enabled;
            toggleAudio.querySelector('i').className = audioTrack.enabled ? 'fas fa-microphone' : 'fas fa-microphone-slash';
        });

        endCall.addEventListener('click', () => {
            this.endCall();
        });
    }

    endCall() {
        // Stop all tracks
        this.localStream.getTracks().forEach(track => track.stop());
        if (this.remoteStream) {
            this.remoteStream.getTracks().forEach(track => track.stop());
        }

        // Close peer connection
        if (this.peerConnection) {
            this.peerConnection.close();
        }

        // Redirect to end call page or close window
        window.location.href = '/';
    }
}

// Initialize video call when the page loads
document.addEventListener('DOMContentLoaded', () => {
    new VideoCall(window.videoCallConfig);
}); 