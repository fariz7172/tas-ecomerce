import bpy
import sys
import os

if len(sys.argv) < 5:
    sys.exit(0)

# Args passed after -- : input_file, output_file
input_glb = sys.argv[-2]
output_glb = sys.argv[-1]

if not os.path.exists(input_glb):
    sys.exit(1)

bpy.ops.wm.read_factory_settings(use_empty=True)
bpy.ops.import_scene.gltf(filepath=input_glb)

# Decimate mesh polygons if > 15k
mesh_objs = [obj for obj in bpy.context.scene.objects if obj.type == 'MESH']
for obj in mesh_objs:
    bpy.context.view_layer.objects.active = obj
    obj.select_set(True)
    if len(obj.data.polygons) > 15000:
        mod = obj.modifiers.new(name="Decimate", type='DECIMATE')
        mod.ratio = 0.5
        bpy.ops.object.modifier_apply(modifier="Decimate")
    bpy.ops.object.origin_set(type='ORIGIN_GEOMETRY', center='BOUNDS')
    obj.location = (0, 0, 0)

# Downscale textures if large
for img in bpy.data.images:
    orig_w, orig_h = img.size[0], img.size[1]
    if orig_w > 1024 or orig_h > 1024:
        img.scale(1024, 1024)
        img.pack()

# Export with Draco & JPEG 75%
bpy.ops.export_scene.gltf(
    filepath=output_glb,
    export_format='GLB',
    use_selection=False,
    export_apply=True,
    export_image_format='JPEG',
    export_jpeg_quality=75,
    export_draco_mesh_compression_enable=True,
    export_draco_mesh_compression_level=7,
    export_materials='EXPORT',
    export_cameras=False,
    export_lights=False
)
