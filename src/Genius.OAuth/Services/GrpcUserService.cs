// This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
// If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
// Copyright (C) 2022 Leszek Pomianowski.
// All Rights Reserved.

using System.Threading.Tasks;
using Genius.OAuth.Data.Contexts;
using GeniusProtocol;
using Grpc.Core;
using Microsoft.Extensions.Logging;

namespace Genius.OAuth.Services
{
    public class GrpcUserService : User.UserBase
    {
        private readonly ILogger<GrpcUserService> _logger;

        private readonly SystemContext _context;

        public GrpcUserService(ILogger<GrpcUserService> logger, SystemContext context)
        {
            _logger = logger;
            _context = context;
        }

        public override Task<UserResponseModel> Register(UserModel request, ServerCallContext context)
        {
            return base.Register(request, context);
        }

        public override Task<UserModel> Update(UserModel request, ServerCallContext context)
        {
            return base.Update(request, context);
        }

        public override Task<UserResponseModel> Get(UserLookupModel request, ServerCallContext context)
        {
            return base.Get(request, context);
        }

        public override Task<UserResponseModel> Delete(UserModel request, ServerCallContext context)
        {
            return base.Delete(request, context);
        }
    }
}
