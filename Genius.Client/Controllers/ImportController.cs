// This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
// If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
// Copyright (C) 2022 Leszek Pomianowski.
// All Rights Reserved.

using Genius.Client.Services;
using Microsoft.AspNetCore.Http;
using Microsoft.AspNetCore.Mvc;
using Microsoft.Extensions.Logging;
using System;
using System.IO;
using System.Text;
using System.Threading.Tasks;

namespace Genius.Client.Controllers
{
    [ApiController]
    [Route("api/import")]
    public class ImportController : ControllerBase
    {
        private readonly ILogger<ExportController> _logger;

        private readonly GrpcExpertClientService _expertClient;

        public ImportController(ILogger<ExportController> logger, GrpcExpertClientService expertClient)
        {
            _logger = logger;
            _expertClient = expertClient;
        }

        [HttpPost]
        [Route("")]
        public async Task<IActionResult> ImportSystem([FromForm] string systemId, [FromForm] IFormFile file)
        {
            if (String.IsNullOrEmpty(systemId))
                return BadRequest("System not found");

            Int32.TryParse(systemId, out int iSystemId);

            if (iSystemId < 1)
                return BadRequest("System not found");

            if (file == null)
                return BadRequest("File not found");

            var fileContent = await ReadFileContent(file);

            return Ok("success");
        }

        private async Task<string> ReadFileContent(IFormFile file)
        {
            var result = new StringBuilder();
            using var reader = new StreamReader(file.OpenReadStream());

            while (reader.Peek() >= 0)
                result.AppendLine(await reader.ReadLineAsync());

            reader.Close();

            return result.ToString();
        }
    }
}