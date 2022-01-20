// This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
// If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
// Copyright (C) 2022 Leszek Pomianowski.
// All Rights Reserved.

using Genius.Data.Contexts;
using GeniusProtocol;
using Grpc.Core;
using Microsoft.EntityFrameworkCore;
using Microsoft.Extensions.Logging;
using System;
using System.Linq;
using System.Threading.Tasks;

namespace Genius.Services
{
    public class GrpcExpertService : GeniusProtocol.Expert.ExpertBase
    {
        private readonly ILogger<GrpcExpertService> _logger;

        private readonly ExpertContext _expertContext;

        public GrpcExpertService(ILogger<GrpcExpertService> logger, ExpertContext expertContext)
        {
            _logger = logger;
            _expertContext = expertContext;
        }

        public override async Task<ExpertResponseModel> Create(ExpertModel request, ServerCallContext context)
        {
            if (string.IsNullOrEmpty(request?.Name) || string.IsNullOrEmpty(request?.Description))
            {
                return new ExpertResponseModel { Id = 0 };
            }

            var insertedSystem = new Data.Models.Expert.System
            {
                Name = request.Name,
                Description = request.Description,
                Question = request.Question ?? "",
                CreatedAt = DateTime.Now,
                UpdatedAt = DateTime.Now,
                Version = "1.0.0",
                Guid = Guid.NewGuid().ToString()
            };

            _expertContext.Systems.Add(insertedSystem);

            await _expertContext.SaveChangesAsync();

            return new ExpertResponseModel { Id = insertedSystem?.Id ?? 0 };
        }

        public override async Task GetAll(ExpertEmptyModel request, IServerStreamWriter<ExpertModel> responseStream, ServerCallContext context)
        {
            var savedSystems = _expertContext.Systems;

            foreach (Data.Models.Expert.System singleSystem in savedSystems)
            {
                await responseStream.WriteAsync(new ExpertModel
                {
                    Id = singleSystem.Id,
                    Version = singleSystem.Version,
                    Guid = singleSystem.Guid,
                    Name = singleSystem.Name,
                    Description = singleSystem.Description,
                    Question = singleSystem.Question,
                    CreatedAt = singleSystem.CreatedAt.ToString(),
                    UpdatedAt = singleSystem.UpdatedAt.ToString()
                });
            }
        }

        public override async Task<ExpertModel> Get(ExpertLookupModel request, ServerCallContext context)
        {
            Data.Models.Expert.System expertSystem = new Data.Models.Expert.System { Id = 0 };

            if (!String.IsNullOrEmpty(request?.Guid))
            {
                expertSystem = await _expertContext.Systems.Where(sys => sys.Guid == request.Guid.Trim()).FirstOrDefaultAsync() ?? new Data.Models.Expert.System { Id = 0 };
            }

            if ((request?.Id ?? 0) > 0)
            {
                expertSystem = await _expertContext.Systems.Where(sys => sys.Id == request.Id).FirstOrDefaultAsync() ?? new Data.Models.Expert.System { Id = 0 };
            }

            if (expertSystem.Id < 1)
            {
                return new ExpertModel();
            }

            return new ExpertModel
            {
                Id = expertSystem.Id,
                Version = expertSystem.Version,
                Guid = expertSystem.Guid,
                Name = expertSystem.Name,
                Description = expertSystem.Description,
                Question = expertSystem.Question,
                CreatedAt = expertSystem.CreatedAt.ToString(),
                UpdatedAt = expertSystem.UpdatedAt.ToString()
            };
        }
    }
}
