from pathlib import Path

from PIL import Image, ImageEnhance, ImageFilter, ImageSequence


SOURCE_DIR = Path("public/images/NAT___JLPT_N5/pages")
OUTPUT_DIR = SOURCE_DIR / "enhanced"
SUPPORTED_EXTENSIONS = {".jpg", ".jpeg", ".gif"}
UPSCALE_FACTOR = 2


def enhance_frame(frame: Image.Image) -> Image.Image:
    frame = frame.convert("RGB")
    resized = frame.resize(
        (frame.width * UPSCALE_FACTOR, frame.height * UPSCALE_FACTOR),
        Image.Resampling.LANCZOS,
    )
    resized = resized.filter(ImageFilter.UnsharpMask(radius=1.8, percent=180, threshold=2))
    resized = ImageEnhance.Contrast(resized).enhance(1.05)
    return resized


def process_image(path: Path) -> None:
    with Image.open(path) as img:
        if getattr(img, "is_animated", False):
            frames = [enhance_frame(frame.copy()) for frame in ImageSequence.Iterator(img)]
            output_path = OUTPUT_DIR / path.name
            frames[0].save(
                output_path,
                save_all=True,
                append_images=frames[1:],
                loop=img.info.get("loop", 0),
                duration=img.info.get("duration", 100),
                optimize=False,
            )
            return

        enhanced = enhance_frame(img)
        output_path = OUTPUT_DIR / path.name
        if path.suffix.lower() in {".jpg", ".jpeg"}:
            enhanced.save(output_path, quality=95, subsampling=0, optimize=True)
        else:
            enhanced.save(output_path)


def main() -> None:
    OUTPUT_DIR.mkdir(exist_ok=True)
    for path in sorted(SOURCE_DIR.iterdir()):
        if path.is_file() and path.suffix.lower() in SUPPORTED_EXTENSIONS:
            process_image(path)
            print(f"Enhanced: {path.name}")


if __name__ == "__main__":
    main()
