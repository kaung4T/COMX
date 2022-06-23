(function ($) {
    var elementSettingsThreejs = {};

    var SCREEN_HEIGHT = window.innerHeight;
    var SCREEN_WIDTH = window.innerWidth;

    var container, stats;
    var camera, controls, scene, renderer;

    var clothGeometry;
    var sphere;
    var object;

    var clock = new THREE.Clock();

    var WidgetElements_ThreeJsHandler = function ($scope, $) {
        console.log($scope);

        elementSettingsThreejs = get_Dyncontel_ElementSettings($scope);


        if (!Detector.webgl)
            Detector.addGetWebGLMessage();

        //alert('sss');

        container = document.createElement('div');
        $scope.find('.dce3d').get(0).appendChild(container);

        init();
        animate();



    };
    function init() {

        //alert( elementSettingsThreejs.color_ground );
        ////// DYN //////
        var coloreSfondo = elementSettingsThreejs.color_landscape;
        var colorePavimento = elementSettingsThreejs.color_ground;
        // scene

        scene = new THREE.Scene();
        scene.background = new THREE.Color(coloreSfondo);

        // if Fog Enabled
        //alert(elementSettingsThreejs.enabled_fog);
        if (elementSettingsThreejs.enabled_fog) {
            scene.fog = new THREE.Fog(coloreSfondo, 500, 10000);
        }
        // camera

        camera = new THREE.PerspectiveCamera(30, window.innerWidth / window.innerHeight, 1, 10000);
        camera.position.x = 1000;
        camera.position.y = 50;
        camera.position.z = 1500;
        scene.add(camera);

        // lights

        var light, materials;

        scene.add(new THREE.AmbientLight(0x666666));

        light = new THREE.DirectionalLight(0xdfebff, 1.75);
        light.position.set(50, 200, 100);
        light.position.multiplyScalar(1.3);

        light.castShadow = true;

        light.shadow.mapSize.width = 1024;
        light.shadow.mapSize.height = 1024;

        var d = 300;

        light.shadow.camera.left = -d;
        light.shadow.camera.right = d;
        light.shadow.camera.top = d;
        light.shadow.camera.bottom = -d;

        light.shadow.camera.far = 1000;

        scene.add(light);

        // cloth material
        var loader = new THREE.TextureLoader();
        /*
         
         var clothTexture = loader.load( 'textures/patterns/circuit_pattern.png' );
         clothTexture.wrapS = clothTexture.wrapT = THREE.RepeatWrapping;
         clothTexture.anisotropy = 16;
         
         var clothMaterial = new THREE.MeshPhongMaterial( {
         specular: 0x030303,
         map: clothTexture,
         side: THREE.DoubleSide,
         alphaTest: 0.5
         } );
         
         // cloth geometry
         clothGeometry = new THREE.ParametricGeometry( clothFunction, cloth.w, cloth.h );
         clothGeometry.dynamic = true;
         
         var uniforms = { texture:  { value: clothTexture } };
         var vertexShader = document.getElementById( 'vertexShaderDepth' ).textContent;
         var fragmentShader = document.getElementById( 'fragmentShaderDepth' ).textContent;
         
         // cloth mesh
         
         object = new THREE.Mesh( clothGeometry, clothMaterial );
         object.position.set( 0, 0, 0 );
         object.castShadow = true;
         scene.add( object );
         
         object.customDepthMaterial = new THREE.ShaderMaterial( {
         uniforms: uniforms,
         vertexShader: vertexShader,
         fragmentShader: fragmentShader,
         side: THREE.DoubleSide
         } );
         
         // sphere
         
         var ballGeo = new THREE.SphereGeometry( ballSize, 20, 20 );
         var ballMaterial = new THREE.MeshPhongMaterial( { color: 0xaaaaaa } );
         
         sphere = new THREE.Mesh( ballGeo, ballMaterial );
         sphere.castShadow = true;
         sphere.receiveShadow = true;
         scene.add( sphere );
         */
        // ground

        //var groundTexture = loader.load( 'http://localhost:8888/poglie17/textures/terrain/grasslight-big.jpg' );
        //alert( elementSettingsThreejs.image_ground.url );
        ////// DYN //////
        if (elementSettingsThreejs.image_ground.url != '') {
            // L'immagine del pavimento
            var textureRepeat = elementSettingsThreejs.ground_texture_repeat;
            var groundTexture = loader.load(elementSettingsThreejs.image_ground.url);
            groundTexture.wrapS = groundTexture.wrapT = THREE.RepeatWrapping;
            groundTexture.repeat.set(textureRepeat, textureRepeat); //.set( 25, 25 );
            groundTexture.anisotropy = 16;
        } else {
            var groundTexture = null;
        }

        // il pavimento
        var groundMaterial = new THREE.MeshPhongMaterial({color: new THREE.Color(colorePavimento), specular: 0x333333, map: groundTexture});

        var groundMesh = new THREE.Mesh(new THREE.PlaneBufferGeometry(20000, 20000), groundMaterial);
        groundMesh.position.y = -250;
        groundMesh.rotation.x = -Math.PI / 2;
        groundMesh.receiveShadow = true;
        scene.add(groundMesh);

        // Il poligono bianco
        var poleGeo = new THREE.BoxGeometry(5, 375, 240);
        var poleMat = new THREE.MeshPhongMaterial({color: 0xffffff, specular: 0x111111, shininess: 100});

        var mesh = new THREE.Mesh(poleGeo, poleMat);
        mesh.position.x = -125;
        mesh.position.y = -62;
        mesh.receiveShadow = true;
        mesh.castShadow = true;
        scene.add(mesh);
        /*
         var mesh = new THREE.Mesh( poleGeo, poleMat );
         mesh.position.x = 125;
         mesh.position.y = - 62;
         mesh.receiveShadow = true;
         mesh.castShadow = true;
         scene.add( mesh );
         
         var mesh = new THREE.Mesh( new THREE.BoxGeometry( 255, 5, 5 ), poleMat );
         mesh.position.y = - 250 + ( 750 / 2 );
         mesh.position.x = 0;
         mesh.receiveShadow = true;
         mesh.castShadow = true;
         scene.add( mesh );
         
         var gg = new THREE.BoxGeometry( 10, 10, 10 );
         var mesh = new THREE.Mesh( gg, poleMat );
         mesh.position.y = - 250;
         mesh.position.x = 125;
         mesh.receiveShadow = true;
         mesh.castShadow = true;
         scene.add( mesh );
         
         var mesh = new THREE.Mesh( gg, poleMat );
         mesh.position.y = - 250;
         mesh.position.x = - 125;
         mesh.receiveShadow = true;
         mesh.castShadow = true;
         scene.add( mesh );
         */
        // renderer

        renderer = new THREE.WebGLRenderer({antialias: true});
        renderer.setPixelRatio(window.devicePixelRatio);
        renderer.setSize(window.innerWidth, window.innerHeight);
        renderer.shadowMap.renderSingleSided = false;

        container.appendChild(renderer.domElement);

        renderer.gammaInput = true;
        renderer.gammaOutput = true;

        renderer.shadowMap.enabled = true;

        // controls

        if ('orbit' == elementSettingsThreejs.controls_type) {
            controls = new THREE.OrbitControls(camera, renderer.domElement);
            controls.maxPolarAngle = Math.PI * 0.5;
            controls.minDistance = 1000;
            controls.maxDistance = 7500;
        } else if ('trackball' == elementSettingsThreejs.controls_type) {
            controls = new THREE.TrackballControls(camera);

            controls.rotateSpeed = 1.0;
            controls.zoomSpeed = 1.2;
            controls.panSpeed = 0.8;

            controls.noZoom = false;
            controls.noPan = false;

            controls.staticMoving = true;
            controls.dynamicDampingFactor = 0.3;

            controls.keys = [65, 83, 68];

            controls.addEventListener('change', render);
        } else if ('fly' == elementSettingsThreejs.controls_type) {
            controls = new THREE.FlyControls(camera);

            controls.movementSpeed = 1000;
            controls.domElement = container;
            controls.rollSpeed = Math.PI / 24;
            controls.autoForward = false;
            controls.dragToLook = false;
        } else if ('transform' == elementSettingsThreejs.controls_type) {
            controls = new THREE.TransformControls(camera, renderer.domElement);
            controls.addEventListener('change', render);
            controls.setMode("translate");
            controls.setSpace("local");
            //var mesh = new THREE.Mesh( geometry, material );
            scene.add(mesh);

            controls.attach(groundMesh);
            scene.add(controls);
        }
        // performance monitor

        stats = new Stats();
        container.appendChild(stats.dom);

        //
        //alert('INIT 3D');
        window.addEventListener('resize', onWindowResize, false);

        //sphere.visible = ! true;

    }

    //

    function onWindowResize() {
        SCREEN_HEIGHT = window.innerHeight;
        SCREEN_WIDTH = window.innerWidth;

        camera.aspect = SCREEN_WIDTH / SCREEN_HEIGHT;
        camera.updateProjectionMatrix();

        renderer.setSize(SCREEN_WIDTH, SCREEN_HEIGHT);

        if ('trackball' == elementSettingsThreejs.controls_type)
            controls.handleResize();

        render();
    }

    //

    function animate() {

        requestAnimationFrame(animate);

        var time = Date.now();
        var delta = clock.getDelta();
        /*var windStrength = Math.cos( time / 7000 ) * 20 + 40;
         
         windForce.set( Math.sin( time / 2000 ), Math.cos( time / 3000 ), Math.sin( time / 1000 ) )
         windForce.normalize()
         windForce.multiplyScalar( windStrength );*/

        //simulate( time );
        //controls.movementSpeed = 0.33 * d;
        //
        if ('fly' == elementSettingsThreejs.controls_type) {
            controls.update(delta);
        } else {
            controls.update();
        }
        render();


    }

    function render() {

        //var p = cloth.particles;

        /*for ( var i = 0, il = p.length; i < il; i ++ ) {
         
         clothGeometry.vertices[ i ].copy( p[ i ].position );
         
         }
         
         clothGeometry.computeFaceNormals();
         clothGeometry.computeVertexNormals();
         
         clothGeometry.normalsNeedUpdate = true;
         clothGeometry.verticesNeedUpdate = true;
         
         sphere.position.copy( ballPosition );
         */
        camera.lookAt(scene.position);

        renderer.render(scene, camera);

        if (stats)
            stats.update();

    }
    // Make sure you run this code under Elementor..
    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/dce-threejs.default', WidgetElements_ThreeJsHandler);
    });
})(jQuery);
